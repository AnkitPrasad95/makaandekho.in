<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/includes/auth.php';
require_auth();

// ---- Menu Groups ----
$menuGroups = [
    'for_buyers'      => 'For Buyers',
    'for_owners'      => 'For Owners',
    'insights'        => 'Insights',
    'builders_agents' => 'Builders & Agents',
];

$activeTab = $_GET['tab'] ?? 'for_buyers';
if (!isset($menuGroups[$activeTab])) $activeTab = 'for_buyers';

// ---- Handle Actions ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $menu_slug      = $_POST['menu_slug'] ?? '';
        $column_heading = trim($_POST['column_heading'] ?? '');
        $item_title     = trim($_POST['item_title'] ?? '');
        $item_url       = trim($_POST['item_url'] ?? '#');
        $column_order   = (int)($_POST['column_order'] ?? 1);
        $item_order     = (int)($_POST['item_order'] ?? 1);

        if ($column_heading && $item_title && isset($menuGroups[$menu_slug])) {
            $pdo->prepare("INSERT INTO mega_menu_items (menu_slug, column_heading, item_title, item_url, column_order, item_order) VALUES (?,?,?,?,?,?)")
                ->execute([$menu_slug, $column_heading, $item_title, $item_url ?: '#', $column_order, $item_order]);
            flash('success', 'Menu item added.');
        } else {
            flash('error', 'Column heading and item title are required.');
        }
        header("Location: " . BASE_URL . "mega-menu.php?tab=$menu_slug");
        exit;
    }

    if ($action === 'edit') {
        $id             = (int)($_POST['id'] ?? 0);
        $column_heading = trim($_POST['column_heading'] ?? '');
        $item_title     = trim($_POST['item_title'] ?? '');
        $item_url       = trim($_POST['item_url'] ?? '#');
        $column_order   = (int)($_POST['column_order'] ?? 1);
        $item_order     = (int)($_POST['item_order'] ?? 1);
        $is_active      = isset($_POST['is_active']) ? 1 : 0;
        $tab            = $_POST['menu_slug'] ?? $activeTab;

        if ($id && $column_heading && $item_title) {
            $pdo->prepare("UPDATE mega_menu_items SET column_heading=?, item_title=?, item_url=?, column_order=?, item_order=?, is_active=? WHERE id=?")
                ->execute([$column_heading, $item_title, $item_url ?: '#', $column_order, $item_order, $is_active, $id]);
            flash('success', 'Menu item updated.');
        }
        header("Location: " . BASE_URL . "mega-menu.php?tab=$tab");
        exit;
    }

    if ($action === 'delete') {
        $id  = (int)($_POST['id'] ?? 0);
        $tab = $_POST['menu_slug'] ?? $activeTab;
        if ($id) {
            $pdo->prepare("UPDATE mega_menu_items SET is_deleted=1, deleted_at=NOW() WHERE id=?")->execute([$id]);
            flash('success', 'Menu item deleted.');
        }
        header("Location: " . BASE_URL . "mega-menu.php?tab=$tab");
        exit;
    }
}

// ---- Fetch items for active tab ----
$stmt = $pdo->prepare("SELECT * FROM mega_menu_items WHERE menu_slug=? AND is_deleted=0 ORDER BY column_order ASC, item_order ASC");
$stmt->execute([$activeTab]);
$items = $stmt->fetchAll();

// Group by column_heading
$columns = [];
foreach ($items as $item) {
    $columns[$item['column_heading']][] = $item;
}

require_once 'includes/header.php';
?>

<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4><i class="fas fa-bars mr-2"></i>Mega Menu Manager</h4>
        <p>Manage dropdown menu items for each navigation link</p>
    </div>
    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
        <i class="fas fa-plus mr-1"></i> Add Menu Item
    </button>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3">
    <?php foreach ($menuGroups as $slug => $label): ?>
    <li class="nav-item">
        <a class="nav-link <?= $activeTab === $slug ? 'active' : '' ?>" href="?tab=<?= $slug ?>">
            <?= $label ?>
            <span class="badge badge-secondary ml-1"><?php
                $c = $pdo->prepare("SELECT COUNT(*) FROM mega_menu_items WHERE menu_slug=? AND is_deleted=0");
                $c->execute([$slug]);
                echo $c->fetchColumn();
            ?></span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>

<!-- Menu Items grouped by Column -->
<?php if (empty($columns)): ?>
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="fas fa-inbox" style="font-size:40px;opacity:.3;"></i>
        <p class="mt-3">No menu items yet for <strong><?= $menuGroups[$activeTab] ?></strong>.</p>
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addItemModal">
            <i class="fas fa-plus mr-1"></i> Add First Item
        </button>
    </div>
</div>
<?php else: ?>
<div class="row">
    <?php foreach ($columns as $heading => $colItems): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-columns mr-2 text-primary"></i><?= htmlspecialchars($heading) ?></span>
                <span class="badge badge-info"><?= count($colItems) ?> items</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                    <?php foreach ($colItems as $item): ?>
                    <tr class="<?= !$item['is_active'] ? 'text-muted' : '' ?>">
                        <td style="padding:10px 16px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (!$item['is_active']): ?>
                                    <span class="badge badge-secondary mr-1" style="font-size:9px;">OFF</span>
                                    <?php endif; ?>
                                    <strong style="font-size:13px;"><?= htmlspecialchars($item['item_title']) ?></strong>
                                    <br><small class="text-muted"><?= htmlspecialchars($item['item_url']) ?></small>
                                </div>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-outline-primary btn-xs edit-btn"
                                            data-id="<?= $item['id'] ?>"
                                            data-column-heading="<?= htmlspecialchars($item['column_heading']) ?>"
                                            data-item-title="<?= htmlspecialchars($item['item_title']) ?>"
                                            data-item-url="<?= htmlspecialchars($item['item_url']) ?>"
                                            data-column-order="<?= $item['column_order'] ?>"
                                            data-item-order="<?= $item['item_order'] ?>"
                                            data-is-active="<?= $item['is_active'] ?>"
                                            data-menu-slug="<?= $activeTab ?>"
                                            data-toggle="modal" data-target="#editItemModal">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Delete this item?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="menu_slug" value="<?= $activeTab ?>">
                                        <button class="btn btn-outline-danger btn-xs"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- ===== ADD ITEM MODAL ===== -->
<div class="modal fade" id="addItemModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="add">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle mr-2 text-primary"></i>Add Menu Item</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Menu Group</label>
            <select name="menu_slug" class="form-control">
                <?php foreach ($menuGroups as $slug => $label): ?>
                <option value="<?= $slug ?>" <?= $activeTab === $slug ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Column Heading <span class="text-danger">*</span></label>
            <input type="text" name="column_heading" class="form-control" placeholder="e.g. RESIDENTIAL" required>
            <small class="text-muted">Items with same heading appear in the same column</small>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Item Title <span class="text-danger">*</span></label>
            <input type="text" name="item_title" class="form-control" placeholder="e.g. Studio Apartments" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Item URL</label>
            <input type="text" name="item_url" class="form-control" placeholder="#" value="#">
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold">Column Order</label>
                    <input type="number" name="column_order" class="form-control" value="1" min="1">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label class="font-weight-bold">Item Order</label>
                    <input type="number" name="item_order" class="form-control" value="1" min="1">
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-plus mr-1"></i>Add Item</button>
      </div>
    </form>
  </div>
</div>

<!-- ===== EDIT ITEM MODAL ===== -->
<div class="modal fade" id="editItemModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content" id="editForm">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" id="editId">
      <input type="hidden" name="menu_slug" id="editMenuSlug">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit mr-2 text-primary"></i>Edit Menu Item</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label class="font-weight-bold">Column Heading <span class="text-danger">*</span></label>
            <input type="text" name="column_heading" id="editColumnHeading" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Item Title <span class="text-danger">*</span></label>
            <input type="text" name="item_title" id="editItemTitle" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="font-weight-bold">Item URL</label>
            <input type="text" name="item_url" id="editItemUrl" class="form-control">
        </div>
        <div class="row">
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Col Order</label>
                    <input type="number" name="column_order" id="editColumnOrder" class="form-control" min="1">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Item Order</label>
                    <input type="number" name="item_order" id="editItemOrder" class="form-control" min="1">
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label class="font-weight-bold">Active</label>
                    <div class="custom-control custom-switch mt-1">
                        <input type="checkbox" class="custom-control-input" id="editIsActive" name="is_active" value="1">
                        <label class="custom-control-label" for="editIsActive">Visible</label>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Save Changes</button>
      </div>
    </form>
  </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
$(document).ready(function() {
  // Populate edit modal
  $(document).on('click', '.edit-btn', function () {
      $('#editId').val($(this).data('id'));
      $('#editMenuSlug').val($(this).data('menu-slug'));
      $('#editColumnHeading').val($(this).data('column-heading'));
      $('#editItemTitle').val($(this).data('item-title'));
      $('#editItemUrl').val($(this).data('item-url'));
      $('#editColumnOrder').val($(this).data('column-order'));
      $('#editItemOrder').val($(this).data('item-order'));
      $('#editIsActive').prop('checked', $(this).data('is-active') == 1);
  });
});
</script>
