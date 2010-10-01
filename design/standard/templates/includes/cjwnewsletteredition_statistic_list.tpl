{* include uri='design:includes/cjwnewsletteredition_statistic_list.tpl'
             page_uri=$page_uri
             edition_node_list=$edition_node_list
             edition_node_list_count=$edition_node_list_count
             view_parameters = $view_parameters
             limit = $limit}

*}

{if is_set( $show_actions_colum )|not}
    {def $show_actions_colum = false()}
{/if}

{if is_set( $can_copy )|not}
    {def $can_copy = true()}
{/if}





<div class="content-navigation-childlist overflow-table">
    <table class="list" cellspacing="0">
        <tr>
            {* Name column *}
            <th class="name">{'Name'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>

           {* Status column *}
            <th class="status">{'Status'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>

            <th>{'Mails'|i18n( 'cjw_newsletter/index' )}</th>
            <th>{'Opened'|i18n( 'cjw_newsletter/index' )}</th>
            <th>{'Bounced'|i18n( 'cjw_newsletter/index' )}</th>

            {* Class type column *}
            {*<th class="class">{'Type'|i18n( 'design/admin/node/view/full' )}</th>*}
            <th class="modified">{'Modified'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>

            {if $show_actions_colum}


            {* Copy column *}
            <th class="copy">&nbsp;</th>

            {* Move column *}
           {* <th class="move">&nbsp;</th>*}

            {* Edit column *}
            <th class="edit">&nbsp;</th>
            {/if}
        </tr>

        {foreach $edition_node_list as $edition_node sequence array( 'bglight', 'bgdark' ) as $style}
        {def $child_name = $edition_node.name|wash
             $node_content = $edition_node.object
             $newsletter_edition_attribute_content = $edition_node.data_map.newsletter_edition.content
             $edition_status = $newsletter_edition_attribute_content.status
             $nl_status_is_draft = $newsletter_edition_attribute_content.is_draft}

         <tr class="{$style}">
             {* Name *}
             <td>
                {*node_view_gui view=line content_node=$edition_node*}
                 {$edition_node.class_identifier|class_icon( 'small' )} <a href={$edition_node.url_alias|ezurl}>{$edition_node.name|wash()}</a>
             </td>
             {* Status *}
             <td>
                 {switch match=$edition_status}
                     {case match='draft'}
                        <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} alt="{$edition_status|wash}" title="{$edition_status|wash}" />
                    {/case}
                    {case match='process'}
                        <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} alt="{$edition_status|wash}" title="{$edition_status|wash}" />
                    {/case}
                    {case match='archive'}
                        <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} alt="{$edition_status|wash}" title="{$edition_status|wash}" />
                    {/case}
                    {case match='abort'}
                        <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} alt="{$edition_status|wash}" title="{$edition_status|wash}" />
                    {/case}
                 {/switch}
            </td>
            <td nowrap>
                {if $edition_status|ne('draft')}
                    {def $current_send_statistic = $newsletter_edition_attribute_content.edition_send_array.current.0.send_items_statistic}

                    {$current_send_statistic.items_send|wash}/{$current_send_statistic.items_count|wash} ({$current_send_statistic.items_send_in_percent|wash}%)

                    {undef $current_send_statistic}
                {/if}
            </td>
            <td nowrap>
            </td>
            <td nowrap>
                {if $edition_status|ne('draft')}
                    {$current_send_statistic.items_bounced|wash}
                {/if}
            </td>

            {* Published *}
            <td class="published" nowrap>{$edition_node.object.modified|l10n( 'shortdatetime' )}</td>

            {if $show_actions_colum}
            {* Copy button *}
            <td>
            {if $can_copy}
            <a href={concat( 'content/copysubtree/', $edition_node.node_id )|ezurl()}><img src={'copy.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="{'Create a copy of <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>
            {else}
            <img src={'copy-disabled.gif'|ezimage} alt="{'Copy'|i18n( 'design/admin/node/view/full' )}" title="" />
            {/if}
            </td>
            {* Edit button *}
            <td>
            {if and( $edition_node.can_edit, $nl_status_is_draft )}
                <form action={'content/action'|ezurl()} method="post">
                    <input type="hidden" value="{$edition_node.node_id}" name="TopLevelNode"/>
                    <input type="hidden" value="{$edition_node.node_id}" name="ContentNodeID"/>
                    <input type="hidden" value="{$edition_node.contentobject_id}" name="ContentObjectID" />
                   {* <input type="hidden" value="{'newsletter/index'}" name="RedirectIfDiscarded" />*}
                    <input type="hidden" name="ContentObjectLanguageCode" value="{$edition_node.object.current_language}">
                    <input name="EditButton" type="image" src={'edit.gif'|ezimage} alt="{'Edit newsletter'|i18n( 'cjw_newsletter/index' )}" title="{'Edit newsletter'|i18n( 'cjw_newsletter/index' )}" />
                </form>

               {* <a href={concat( 'content/edit/', $edition_node.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'design/admin/node/view/full' )}" title="{'Edit <%child_name>.'|i18n( 'design/admin/node/view/full',, hash( '%child_name', $child_name ) )|wash}" /></a>*}
            {else}
                <img src={'edit-disabled.gif'|ezimage} alt="{'Edit newsletter'|i18n( 'cjw_newsletter/index' )}" title="" />
            {/if}
            </td>
           {/if}
        </tr>

        {undef $child_name $node_content $newsletter_edition_attribute_content $edition_status $nl_status_is_draft}
        {/foreach}
    </table>
</div>



