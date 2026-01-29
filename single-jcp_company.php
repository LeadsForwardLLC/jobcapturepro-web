<?php
/**
 * Single Company Template (jcp_company CPT)
 *
 * Renders company profile via JavaScript (data-jcp-page="company").
 * Used when viewing a single company; /company URLs are routed here via template-routes.
 *
 * @package JCP_Core
 */

get_header();
?>
<div id="jcp-app" data-jcp-page="company"></div>
<?php
get_footer();
