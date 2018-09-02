easyBlacklist.page.Blacklist = function(config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'ebl-panel-blacklist',
			renderTo: 'ebl-panel-blacklist-div'
		}]
	});
	easyBlacklist.page.Blacklist.superclass.constructor.call(this, config);
};
Ext.extend(easyBlacklist.page.Blacklist, MODx.Component);
Ext.reg('ebl-page-blacklist', easyBlacklist.page.Blacklist);

easyBlacklist.panel.Blacklist = function(config) {
	config = config || {};
	Ext.apply(config,{
		border: false,
		baseCls: 'modx-formpanel',
		layout: 'anchor',
		items: [{
			html: '<h2>'+_('ebl_blacklist')+'</h2>',
			border: false,
			cls: 'modx-page-header container'
		}, {
			xtype: 'modx-tabs',
			bodyStyle: 'padding: 10px',
			defaults: { border: false ,autoHeight: true },
			border: true,
			activeItem: 0,
			hideMode: 'offsets',
			items: [{
				title: _('ebl_blacklist'),
				items: [{
					xtype: 'ebl-grid-blacklist',
					preventRender: true
				}]
			}]
		}]
	});
	easyBlacklist.panel.Blacklist.superclass.constructor.call(this, config);
};
Ext.extend(easyBlacklist.panel.Blacklist, MODx.Panel);
Ext.reg('ebl-panel-blacklist', easyBlacklist.panel.Blacklist);