/**
 * Created by polbatllo on 17/02/16.
 */
var AdminLTEOptions = {
    animationSpeed: 250,
    //Box Widget Plugin. Enable this plugin
    //to allow boxes to be collapsed and/or removed
    enableBoxWidget: true,
    //Box Widget plugin options
    boxWidgetOptions: {
        boxWidgetIcons: {
            //Collapse icon
            collapse: 'fa-angle-up',
            //Open icon
            open: 'fa-angle-down',
            //Remove icon
            remove: 'fa-times'
        },
        boxWidgetSelectors: {
            //Remove button selector
            remove: '[data-widget="remove"]',
            //Collapse button selector
            collapse: '[data-widget="collapse"]'
        }
    },
    directChat: {
        //Enable direct chat by default
        enable: false,
        //The button to open and close the chat contacts pane
        contactToggleSelector: '[data-widget="chat-pane-toggle"]'
    },
    enableBoxRefresh: true
};
