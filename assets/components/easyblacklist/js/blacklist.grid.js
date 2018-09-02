easyBlacklist.grid.Blacklist = function(config) {
	config = config || {};
	this.sm = new Ext.grid.CheckboxSelectionModel();
	Ext.applyIf(config, {
		id: 'ebl-grid-blacklist',
		url: easyBlacklist.config.connector_url,
		baseParams: {
			action: 'blacklist/getlist'
		},
		sm: this.sm,
		fields: ['id','reason','ip','hostname','email','username','notes','active', 'attempts', 'createdon', 'actions'],
		columns: this.getColumns(),
		tbar: [{
				text: _('ebl_blacklist_add'),
				handler: this.createItem,
				scope: this
			}
		],
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0
		},
		listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		},
		paging: true,
		remoteSort: true,
		autoHeight: true
	});
	easyBlacklist.grid.Blacklist.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist.grid.Blacklist,MODx.grid.Grid,{
	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = easyBlacklist.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},
	getColumns: function() {
		return [this.sm, {
			header: 'ID',
			dataIndex: 'id',
			hidden: true,
			width: 20
		}, {
			header: _('ebl_blacklist_grid_ip'),
			dataIndex: 'ip',
			sortable: true,
			width: 80
		}, {
			header: _('ebl_blacklist_grid_reason'),
			dataIndex: 'reason',
			sortable: false,
			width: 200
		}, {
			header: _('ebl_blacklist_grid_notes'),
			dataIndex: 'notes',
			sortable: false,
			width: 150
		}, {
			header: _('ebl_blacklist_grid_attempts'),
			dataIndex: 'attempts',
			sortable: false,
			width: 60
		}, {
			header: _('ebl_blacklist_grid_username'),
			dataIndex: 'username',
			sortable: false,
			width: 80
		}, {
			header: _('ebl_blacklist_grid_createdon'),
			dataIndex: 'createdon',
			sortable: false,
			width: 80
		}, {
			header: _('ebl_blacklist_grid_active'),
			dataIndex: 'active',
			renderer: easyBlacklist.utils.renderBoolean,
			sortable: false,
			width: 50
		}, {
			dataIndex: 'actions',
			renderer: easyBlacklist.utils.renderActions,
			sortable: false,
			width: 120,
			fixed: true,
			id: 'actions'
		}];
	},
	createItem: function(btn,e) {
		var w = MODx.load({
			xtype: 'ebl-window-item-create',
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					},
					scope: this
				},
				hide: {
					fn: function () {
						setTimeout(function () {
							w.destroy()
						}, 200);
					}
				}
			}
		});
		w.reset();
		w.setValues({active: true});
		w.show(e.target);
	},
	updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: easyBlacklist.config.connector_url,
			params: {
				action: 'blacklist/get',
				id: id
			},
			listeners: {
				success: { fn:function(r) {
					if (!this.windows.updateItem) {
						var w = MODx.load({
							xtype: 'ebl-window-item-update',
							record: r,
							listeners: {
								success: {fn:function() { this.refresh(); },scope:this},
								hide: {
									fn: function () {
										setTimeout(function () {
											w.destroy()
										}, 200);
									}
								}
							}
						});
					}
					w.reset();
					w.setValues(r.object);
					w.show(e.target);
				}, scope:this }
			}
		});
	},
	removeItem: function(btn,e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		
		MODx.msg.confirm({
			title: _('ebl_blacklist_remove'),
			text: ids.length > 1
				? _('ebl_blacklist_items_remove_confirm')
				: _('ebl_blacklist_item_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'blacklist/remove',
				ids: Ext.util.JSON.encode(ids)
			},
			listeners: {
				success: {
					fn: function (r) {
						this.refresh();
					}, scope: this
				}
			}
		});
	},
	disableItem: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: easyBlacklist.config.connector_url,
			params: {
				action: 'blacklist/disable',
				ids: Ext.util.JSON.encode(ids)
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		})
	},

	enableItem: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: easyBlacklist.config.connector_url,
			params: {
				action: 'blacklist/enable',
				ids: Ext.util.JSON.encode(ids)
			},
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		})
	},
	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	},
	_getSelectedIds: function () {
		var ids = [];
		var selected = this.getSelectionModel().getSelections();

		for (var i in selected) {
			if (!selected.hasOwnProperty(i)) {
				continue;
			}
			ids.push(selected[i]['id']);
		}

		return ids;
	}
});
Ext.reg('ebl-grid-blacklist',easyBlacklist.grid.Blacklist);

/** ********************************* **/
easyBlacklist.window.CreateItem = function(config) {
	config = config || {};
	config.id = 'easyblacklist-window-create';

	Ext.applyIf(config,{
		title: _('ebl_blacklist_item_create'),
		autoHeight: true,
		url: easyBlacklist.config.connector_url,
		maximizable: false,
		action: 'blacklist/create',
		fields: [{
			xtype: 'hidden',
			name: 'id',
			id: config.id + '-id'
		}, {
			xtype: 'textfield',
			fieldLabel: _('ebl_blacklist_grid_ip'),
			name: 'ip',
			id: config.id + '-ip',
			anchor: '100%'
		}, {
			xtype: 'textarea',
			fieldLabel: _('ebl_blacklist_grid_reason'),
			name: 'reason',
			id: config.id + '-reason',
			height: 80,
			anchor: '100%'
		}, {
			xtype: 'textarea',
			fieldLabel: _('ebl_blacklist_grid_notes'),
			name: 'notes',
			id: config.id + '-notes',
			height: 60,
			anchor: '100%'
		}, {
			xtype: 'xcheckbox',
			boxLabel: _('ebl_blacklist_grid_active'),
			name: 'active',
			id: config.id + '-active',
			checked: true
		}],
		keys: [{
			key: Ext.EventObject.ENTER,
			shift: true,
			fn: function() { this.submit() },
			scope: this
		}]
	});
	easyBlacklist.window.CreateItem.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist.window.CreateItem,MODx.Window);
Ext.reg('ebl-window-item-create',easyBlacklist.window.CreateItem);

easyBlacklist.window.UpdateItem = function(config) {
	config = config || {};
	config.id = 'easyblacklist-window-update';

	Ext.applyIf(config,{
		title: _('ebl_blacklist_item_update'),
		action: 'blacklist/update'
	});
	easyBlacklist.window.UpdateItem.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist.window.UpdateItem, easyBlacklist.window.CreateItem);
Ext.reg('ebl-window-item-update', easyBlacklist.window.UpdateItem);