<?php
/**
 * Behavior
 *
 * This plugin's TypedTags behavior will be attached whenever Node model is loaded.
 */
    Croogo::hookBehavior('Node', 'TypedTags.TypedTags', array());

/**
 * Admin tab
 *
 * When adding/editing Content (Nodes),
 * an extra tab with title 'TypedTags' will be shown with markup generated from the plugin's admin_tab_node element.
 */
    Croogo::hookAdminTab('Nodes/admin_add', 'TypedTags', 'typed_tags.admin_tab_node');
    Croogo::hookAdminTab('Nodes/admin_edit', 'TypedTags', 'typed_tags.admin_tab_node');
?>