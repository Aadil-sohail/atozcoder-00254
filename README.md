## Database Updates

### 2026-11-25
- New table `racks`
- New table `shelves`
- New column `products.shelf_id` (nullable, → `shelves`, set to null if the shelf row is removed)
- New permissions: `view racks`, `create racks`, `edit racks`, `delete racks`, `view shelves`, `create shelves`, `edit shelves`, `delete shelves`

### 2026-10-29
- New table `inventory_logs`
- New table `stock_adjustments`
- New permissions: `view stock adjustments`, `create stock adjustments`, `edit stock adjustments`
