<div class="block">

    <table cellspacing="0" cellpadding="0" class="list">
        <tr>
            <th width="61%" class="name" colspan="2">{*<a href={$newsletter_system_node.url_alias|ezurl} title="{$newsletter_system_node.name|wash()}">{'cjw_newsletter_system'|class_icon( 'small' )}</a> *}{$newsletter_system_node.name|wash()}</th>
        </tr>
        {def $nl_list_class_filter_array = array( 'cjw_newsletter_list',
                                                  'cjw_newsletter_list_virtual' )

             $newsletter_list_node_list = fetch('content', 'list',
                                                                hash( 'parent_node_id', $newsletter_system_node.node_id,
                                                                      'class_filter_type', 'include',
                                                                      'class_filter_array', $nl_list_class_filter_array
                                                                     ))}

        {foreach $newsletter_list_node_list as $newsletter_list_node sequence array( 'bglight', 'bgdark' ) as $style}

            <tr class="{$style}">
                <td width="61%" align="left">{$newsletter_list_node.class_identifier|class_icon( 'small' )} <a href={$newsletter_list_node.url_alias|ezurl}>{$newsletter_list_node.name|wash()} / {$newsletter_list_node.data_map.title.content|wash()}</a></td>
                <td align="left" width="39%" nowrap>
                    {if $newsletter_list_node.can_create}
                    <form action={'content/action'|ezurl()} name="CreateNewNewsletterEdition" method="post">
                        <input type="hidden" value="{ezini( 'RegionalSettings', 'ContentObjectLocale' )}" name="ContentLanguageCode"/>
                        <input type="hidden" value="{$newsletter_list_node.node_id}" name="ContentNodeID"/>
                        <input type="hidden" value="{$newsletter_list_node.node_id}" name="NodeID"/>
                        <input type="hidden" value="cjw_newsletter_edition" name="ClassIdentifier"/>
                        <input class="button" type="submit" name="NewButton" value="{'Create newsletter here'|i18n( 'cjw_newsletter/index' )}" />
                    </form>
                    {/if}
                </td>
            </tr>





            {* edition *}
            {def $edition_draft_node_list = fetch('content','list',
                                                                hash('parent_node_id', $newsletter_list_node.node_id,
                                                                     'class_filter_type', 'include',
                                                                     'class_filter_array', array( 'cjw_newsletter_edition' ),
                                                                     'extended_attribute_filter',
                                                                        hash( 'id', 'CjwNewsletterEditionFilter',
                                                                              'params', hash( 'status', 'draft' ) )
                                                                                 ) )}
            {if $edition_draft_node_list|count|gt(0)}
                {foreach $edition_draft_node_list as $edition_draft_node}
                    <tr class="{$style}">
                    <td width="61%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} title="draft" /> <a href={$edition_draft_node.url_alias|ezurl}>{$edition_draft_node.name|wash()}</a></td>
                    <td width="39%">
                        {if $edition_draft_node.can_edit}
                        <form action={'content/action'|ezurl()} method="post">
                            <input type="hidden" value="{$edition_draft_node.node_id}" name="TopLevelNode"/>
                            <input type="hidden" value="{$edition_draft_node.node_id}" name="ContentNodeID"/>
                            <input type="hidden" value="{$edition_draft_node.contentobject_id}" name="ContentObjectID" />
                            <input type="hidden" value="{'newsletter/index'}" name="RedirectIfDiscarded" />
                            <input type="hidden" name="ContentObjectLanguageCode" value="{$edition_draft_node.object.current_language}" />
                            <input class="button" type="submit" title="{'Edit newsletter'|i18n( 'cjw_newsletter/index' )}" value="{'Edit'|i18n( 'cjw_newsletter/index' )}" name="EditButton" />
                        </form>
                        {/if}
                    </tr>
                {/foreach}
            {/if}
            {undef $edition_draft_node_list}



            {* virtual list *}

            {def $newsletter_v_list_node_list = fetch('content', 'list',
                                                                    hash( 'parent_node_id',$newsletter_list_node.node_id,
                                                                        'class_filter_type', 'include',
                                                                        'class_filter_array', array( 'cjw_newsletter_list_virtual' )
                                                                        ))}


            {foreach $newsletter_v_list_node_list as $newsletter_v_list_node sequence array( 'bglight', 'bgdark' ) as $style}

            <tr class="{$style}">
                <td width="61%" align="left">&nbsp;&nbsp;{$newsletter_v_list_node.class_identifier|class_icon( 'small' )} <a href={$newsletter_v_list_node.url_alias|ezurl}>{$newsletter_v_list_node.name|wash()} / {$newsletter_v_list_node.data_map.title.content|wash()}</a></td>
                <td align="left" width="39%" nowrap>
                    {if $newsletter_v_list_node.can_create}
                    <form action={'content/action'|ezurl()} name="CreateNewNewsletterEdition" method="post">
                        <input type="hidden" value="{ezini( 'RegionalSettings', 'ContentObjectLocale' )}" name="ContentLanguageCode"/>
                        <input type="hidden" value="{$newsletter_v_list_node.node_id}" name="ContentNodeID"/>
                        <input type="hidden" value="{$newsletter_v_list_node.node_id}" name="NodeID"/>
                        <input type="hidden" value="cjw_newsletter_edition" name="ClassIdentifier"/>
                        <input class="button" type="submit" name="NewButton" value="{'Create newsletter here'|i18n( 'cjw_newsletter/index' )}" />
                    </form>
                    {/if}
                </td>
            </tr>


            {* virtual edition *}
            {def $edition_draft_node_list = fetch('content','list',
                                                                hash('parent_node_id', $newsletter_v_list_node.node_id,
                                                                     'class_filter_type', 'include',
                                                                     'class_filter_array', array( 'cjw_newsletter_edition' ),
                                                                     'extended_attribute_filter',
                                                                        hash( 'id', 'CjwNewsletterEditionFilter',
                                                                              'params', hash( 'status', 'draft' ) )
                                                                                 ) )}
            {if $edition_draft_node_list|count|gt(0)}
                {foreach $edition_draft_node_list as $edition_draft_node}
                    <tr class="{$style}">
                    <td width="61%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} title="draft" /> <a href={$edition_draft_node.url_alias|ezurl}>{$edition_draft_node.name|wash()}</a></td>
                    <td width="39%">
                        {if $edition_draft_node.can_edit}
                        <form action={'content/action'|ezurl()} method="post">
                            <input type="hidden" value="{$edition_draft_node.node_id}" name="TopLevelNode"/>
                            <input type="hidden" value="{$edition_draft_node.node_id}" name="ContentNodeID"/>
                            <input type="hidden" value="{$edition_draft_node.contentobject_id}" name="ContentObjectID" />
                            <input type="hidden" value="{'newsletter/index'}" name="RedirectIfDiscarded" />
                            <input type="hidden" name="ContentObjectLanguageCode" value="{$edition_draft_node.object.current_language}" />
                            <input class="button" type="submit" title="{'Edit newsletter'|i18n( 'cjw_newsletter/index' )}" value="{'Edit'|i18n( 'cjw_newsletter/index' )}" name="EditButton" />
                        </form>
                        {/if}
                    </tr>
                {/foreach}
            {/if}
            {undef $edition_draft_node_list}


            {/foreach}
            {undef $newsletter_v_list_node_list}




        {/foreach}
    </table>
</div>
