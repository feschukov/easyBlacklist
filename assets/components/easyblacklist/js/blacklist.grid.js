easyBlacklist.grid.Blacklist = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		id: 'ebl-grid-blacklist'
		,url: easyBlacklist.config.connector_url
		,baseParams: {
			action: 'blacklist/getlist'
		}
		,fields: ['id','reason','ip','hostname','email','username','notes','active']
		,autoHeight: true
		,paging: true
		,pageSize: 20
		,remoteSort: true
		,columns: this.getColumns()
		,tbar: [/*{
				text: '<i class="'+ (MODx.modx23 ? 'icon icon-list' : 'bicon-list') + '"></i> ' + _('ebl_bulk_actions')
				,menu: [{
					text: _('ebl_blacklist_active_selected')
					,handler: this.enableItem
					,scope: this
				},{
					text: _('ebl_blacklist_deactive_selected')
					,handler: this.disableItem
					,scope: this
				},'-',{
					text: _('ebl_blacklist_remove_selected')
					,handler: this.removeItem
					,scope: this
				}]
			}
			,*/{
				text: _('ebl_blacklist_create')
				,handler: this.createItem
				,scope: this
			}
		]
		,listeners: {
			rowDblClick: function(grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateItem(grid, e, row);
			}
		}
	});
	easyBlacklist.grid.Blacklist.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist.grid.Blacklist,MODx.grid.Grid,{
	windows: {}

	,getMenu: function() {
		var m = [];
		m.push({
			text: _('ebl_blacklist_update')
			,handler: this.updateItem
		});
		/*m.push({
			text: _('ebl_blacklist_active')
			,handler: this.activeItem
		});*/
		m.push('-');
		m.push({
			text: _('ebl_blacklist_remove')
			,handler: this.removeItem
		});
		this.addContextMenuItem(m);
	}

	,getColumns: function() {
		all = {
			id: {hidden: false, sortable: true, width: 20}
			,ip: {sortable: true, width: 40}
			,reason: {sortable: true, width: 130}
			,active: {sortable: true, width: 20, editor: {xtype:'combo-boolean', renderer:'boolean'}}
		};
		var columns = [];
		for(var field in all) {
			Ext.applyIf(all[field], {
				header: _('ebl_blacklist_grid_' + field)
				,dataIndex: field
			});
			columns.push(all[field]);
		}
		return columns;
	}
	
	,createItem: function(btn,e) {
		if (!this.windows.createItem) {
			this.windows.createItem = MODx.load({
				xtype: 'ebl-window-item-create'
				,listeners: {
					'success': {fn:function() { this.refresh(); },scope:this}
				}
			});
		}
		this.windows.createItem.fp.getForm().reset();
		this.windows.createItem.show(e.target);
	}

	,updateItem: function(btn,e,row) {
		if (typeof(row) != 'undefined') {this.menu.record = row.data;}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: easyBlacklist.config.connector_url
			,params: {
				action: 'blacklist/get'
				,id: id
			}
			,listeners: {
				success: {fn:function(r) {
					if (!this.windows.updateItem) {
						this.windows.updateItem = MODx.load({
							xtype: 'ebl-window-item-update'
							,record: r
							,listeners: {
								'success': {fn:function() { this.refresh(); },scope:this}
							}
						});
					}
					this.windows.updateItem.fp.getForm().reset();
					this.windows.updateItem.fp.getForm().setValues(r.object);
					this.windows.updateItem.show(e.target);
				},scope:this}
			}
		});
	}

	,removeItem: function(btn,e) {
		if (!this.menu.record) return false;
		
		MODx.msg.confirm({
			title: _('ebl_blacklist_item_remove')
			,text: _('ebl_blacklist_item_remove_confirm')
			,url: this.config.url
			,params: {
				action: 'blacklist/remove'
				,ids: this.menu.record.id
			}
			,listeners: {
				'success': {fn:function(r) { this.refresh(); },scope:this}
			}
		});
	}

});
Ext.reg('ebl-grid-blacklist',easyBlacklist.grid.Blacklist);

easyBlacklist.window.CreateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'mecitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('ebl_blacklist_item_create')
		,id: this.ident
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,url: easyBlacklist.config.connector_url
		,action: 'blacklist/create'
		,fields: [
			{xtype: 'textfield',fieldLabel: _('ebl_blacklist_grid_ip'),name: 'ip',id: 'ebl-'+this.ident+'-ip',anchor: '99%'}
			,{xtype: 'textarea',fieldLabel: _('ebl_blacklist_grid_reason'),name: 'reason',id: 'ebl-'+this.ident+'-reason',height: 150,anchor: '99%'}
			,{xtype: 'checkbox',fieldLabel: _('ebl_blacklist_grid_active'),name: 'active',id: 'ebl-'+this.ident+'-active',anchor: '10%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	easyBlacklist.window.CreateItem.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist.window.CreateItem,MODx.Window);
Ext.reg('ebl-window-item-create',easyBlacklist.window.CreateItem);

easyBlacklist.window.UpdateItem = function(config) {
	config = config || {};
	this.ident = config.ident || 'meuitem'+Ext.id();
	Ext.applyIf(config,{
		title: _('ebl_blacklist_item_update')
		,id: this.ident
		,pageSize: Math.round(MODx.config.default_per_page / 2)
		,autoHeight: true
		,url: easyBlacklist.config.connector_url
		,action: 'blacklist/update'
		,fields: [
			{xtype: 'hidden',name: 'id',id: 'ebl-'+this.ident+'-id'}
			,{xtype: 'textfield',fieldLabel: _('ebl_blacklist_grid_ip'),name: 'ip',id: 'ebl-'+this.ident+'-ip',anchor: '99%'}
			,{xtype: 'textarea',fieldLabel: _('ebl_blacklist_grid_reason'),name: 'reason',id: 'ebl-'+this.ident+'-reason',height: 150,anchor: '99%'}
			,{xtype: 'checkbox',fieldLabel: _('ebl_blacklist_grid_active'),name: 'active',id: 'ebl-'+this.ident+'-active',anchor: '10%'}
		]
		,keys: [{key: Ext.EventObject.ENTER,shift: true,fn: function() {this.submit() },scope: this}]
	});
	easyBlacklist.window.UpdateItem.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist.window.UpdateItem,MODx.Window);
Ext.reg('ebl-window-item-update',easyBlacklist.window.UpdateItem);