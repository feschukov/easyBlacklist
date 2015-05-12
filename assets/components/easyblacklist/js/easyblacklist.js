var easyBlacklist = function(config) {
	config = config || {};
	easyBlacklist.superclass.constructor.call(this,config);
};
Ext.extend(easyBlacklist,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config:{},view:{}
});
Ext.reg('easyblacklist',easyBlacklist);
easyBlacklist = new easyBlacklist();
easyBlacklist.PanelSpacer = { html: '<br />' ,border: false, cls: 'easyblacklist-panel-spacer' };