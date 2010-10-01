{set-block variable=$subject scope=root}{ezini('NewsletterMailSettings', 'EmailSubjectPrefix', 'cjw_newsletter.ini')} {$contentobject.name|wash}{/set-block}{set-block variable=$html_mail}<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{$#subject}</title>
</head>
<body>
<table id="table-main">
<tr align="left">
    {def $edition_data_map = $contentobject.data_map}
    <td valign="top">
        <table width="600" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
            <tr>
                <td><a href="http://www.cjw-network.com" title="CJW Network - Developers united in eZ Publish"><img src={'images/newsletter/skin/default/cjw-newsletter_header.jpg'|ezdesign()} border="0" width="600" height="100" alt="CJW Network - Developers united in eZ Publish" /></a></td>
            </tr>
            <tr>
                <td style="padding: 20px 0 0 15px">
                    {* Titel *}
                    {if $edition_data_map.title.has_content}
                        <h1>{$edition_data_map.title.content|wash()}</h1>
                    {/if}
                </td>
            </tr>
            <tr>
                <td style="padding: 0 30px 0 15px">
                    {* Text *}
                    {if $edition_data_map.description.has_content}
                        {$edition_data_map.description.content.output.output_text|wash(xml)}
                    {/if}
                    <br />
                </td>
            </tr>
            {if $list_items|count|ne(0)}
            <tr>
                {*
                   mainarea
                *}
                <td style="padding: 0 30px 0 15px">
                    {def $list_items = fetch('content', 'list', hash( 'parent_node_id', $contentobject.contentobject.main_node_id,
                                                                      'sort_by', array( 'priority' , true() ),
                                                                      'class_filter_type', 'include',
                                                                      'class_filter_array', array( 'cjw_newsletter_article' ) ) )
                    }

                    {*
                        show subarticles
                    *}
                    {foreach $list_items as $attribute}

                        {*
                            title
                        *}
                        {if $attribute.data_map.title.has_content}
                            <h2>{attribute_view_gui attribute=$attribute.data_map.title}</h2>
                        {/if}

                        {*
                            text
                        *}
                        {if $attribute.data_map.short_description.has_content}
                            {attribute_view_gui attribute=$attribute.data_map.short_description}
                        {/if}

                    {/foreach}

                </td>
            </tr>
            {/if}
            <tr>
                <td><a href="http://www.cjw-network.com" title="CJW Network - Developers united in eZ Publish"><img src={'images/newsletter/skin/default/cjw-newsletter_footer.gif'|ezdesign()} width="600" height="75" border="0" alt="-- www.CJW-Network.com --" /></a></td>
            </tr>
            <tr>
                <td style="padding: 10px 30px 10px 10px;">
                    <p>
                    {'To unsubscribe from this newsletter please visit the following link'|i18n('cjw_newsletter/skin/default')}:
                                <a href="{'/newsletter/unsubscribe/#_hash_unsubscribe_#'|ezurl('no')}">{'unsubscribe'|i18n('cjw_newsletter/skin/default')}</a>
                    <br />
                    &copy; {currentdate()|datetime( 'custom', '%Y' )} <a href="http://www.cjw-network.com">www.CJW-Network.com</a>
                    </p>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
</body></html>
{/set-block}{$html_mail|cjw_newsletter_str_replace(
                            array( '<body>',
                                   '<table id="table-main">',
                                   '<a',
                                   '<li>',
                                   '<p>',
                                   '<h1>',
                                   '<h2>',
                                   '<h3>',
                                   ' />',

                                   '     ',
                                   '   ',
                                   '  ',
                                   '  ',
                                   '> <'
                                    ),
                            array( '<body bgcolor="#e1ebd2" text="#666666" link="#666666" vlink="#666666" alink="#666666" style="margin:0;padding:0;">',
                                   '<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#e1ebd2" style="margin:0;padding:0;background-color: #e1ebd2; height: 100%;width: 100%">',
                                   '<a style="color:#666666;font-family:arial,helvetica,sans-serif;padding:0;line-height:1.5;"',
                                   '<li style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:0.75em;padding:0;line-height:1.5;">',
                                   '<p style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:0.75em;padding:0;line-height:1.5;">',
                                   '<h1 style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:1.75em;font-weight:bold;line-height:1;padding:0">',
                                   '<h2 style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:1.3em;font-weight:bold;line-height:1;padding:0">',
                                   '<h3 style="color:#666666;font-family:arial,helvetica,sans-serif;font-size:1.2em;font-weight:bold;line-height:1;padding:0">',
                                   '>',

                                   '',
                                   '',
                                   ' ',
                                   ' ',
                                   '><'
                                    )
                                   )}