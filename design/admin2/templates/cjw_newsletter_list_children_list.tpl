<div class="content-navigation-childlist">
    <table class="list" cellspacing="0">
        <tr>
            {* Remove column *}
            <th class="remove"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}" title="{'Invert selection.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}" onclick="ezjs_toggleCheckboxes( document.children, 'DeleteIDArray[]' ); return false;" /></th>

            {* Name column *}
            <th class="name">{'Name'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>

           {* Status column *}
            <th class="status">{'Status'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>


            {* Class type column *}
            <th class="published">{'Published'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>

            {* Priority column *}
            {if eq( $node.sort_array[0][0], 'priority' )}
                <th class="priority">{'Priority'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}</th>
            {/if}

            {* Edit column *}
            <th class="edit">&nbsp;</th>
        </tr>

        {foreach $children as $child sequence array( bglight, bgdark ) as $style}
           {def $child_name = $child.name|wash
                $node_name = $node.name
                $nodeContent = $child.object}


           {def $editionAttributeContent = $child.data_map.newsletter_edition.content
                $editionStatus = $editionAttributeContent.status}

           <tr class="{$style}">

                {* Remove checkbox *}
                <td>
                {if $child.can_remove}
                    <input type="checkbox" name="DeleteIDArray[]" value="{$child.node_id}" title="{'Use these checkboxes to select items for removal. Click the "Remove selected" button to  remove the selected items.'|i18n( 'design/admin/node/view/full' )|wash()}" />
                    {else}
                    <input type="checkbox" name="DeleteIDArray[]" value="{$child.node_id}" title="{'You do not have permission to remove this item.'|i18n( 'design/admin/node/view/full' )}" disabled="disabled" />
                {/if}
                </td>

                {* Name *}
                <td>{node_view_gui view=line content_node=$child}
                    {if $nodeContent.class_identifier|eq('user')}
                        {if not($nodeContent.data_map['user_account'].content.is_enabled)}
                           <span class="userstatus-disabled">{'(disabled)'|i18n("cjw_newsletter/cjw_newsletter_list_children_list")}</span>
                        {/if}
                        {if $nodeContent.data_map['user_account'].content.is_locked}
                           <span class="userstatus-disabled">{'(locked)'|i18n("cjw_newsletter/cjw_newsletter_list_children_list")}</span>
                        {/if}
                    {/if}
                </td>

                {* Status *}
                <td>
                    {switch match=$editionStatus}
                        {case match='draft'}
                           <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_draft.png'|ezdesign} alt="{$editionStatus}" title="{$editionStatus}" />
                       {/case}
                       {case match='process'}
                           <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_process.png'|ezdesign} alt="{$editionStatus}" title="{$editionStatus}" />
                       {/case}
                       {case match='archive'}
                           <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_archive.png'|ezdesign} alt="{$editionStatus}" title="{$editionStatus}" />
                       {/case}
                       {case match='abort'}
                           <img src={'images/newsletter/icons/crystal-newsletter/16x16/newsletter_abort.png'|ezdesign} alt="{$editionStatus}" title="{$editionStatus}" />
                       {/case}
                    {/switch}
               </td>

               {* Published *}
               <td class="published">{$child.object.published|l10n( shortdatetime )}</td>

               {* Priority *}
                   {if eq( $node.sort_array[0][0], 'priority' )}
                       <td>
                           {if $node.can_edit}
                               <input type="text" name="Priority[]" size="3" value="{$child.priority}" title="{'Use the priority fields to control the order in which the items appear. You can use both positive and negative integers. Click the "Update priorities" button to apply the changes.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )|wash()}" />
                               <input type="hidden" name="PriorityID[]" value="{$child.node_id}" />
                           {else}
                               <input type="text" name="Priority[]" size="3" value="{$child.priority}" title="{'You are not allowed to update the priorities because you do not have permission to edit <%node_name>.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list',, hash( '%node_name', $node_name ) )|wash}" disabled="disabled" />
                           {/if}
                       </td>
                   {/if}

                {* Edit button *}
                <td>
                   {if $child.can_edit}
                       {* nur edit button anzeigen wenn edition nicht in process*}
                       {if $editionAttributeContent.is_draft|not}
                           <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}" title="{'The edition %child_name is already in sending process.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list',, hash( '%child_name', $child_name ) )|wash}" />
                       {else}
                           <a href={concat( 'content/edit/', $child.contentobject_id )|ezurl}><img src={'edit.gif'|ezimage} alt="{'Edit'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}" title="{'Edit <%child_name>.'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list',, hash( '%child_name', $child_name ) )|wash}" /></a>
                       {/if}
                   {else}
                       <img src={'edit-disabled.gif'|ezimage} alt="{'Edit'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list' )}" title="{'You do not have permission to edit "%child_name".'|i18n( 'cjw_newsletter/cjw_newsletter_list_children_list',, hash( '%child_name', $child_name ) )|wash}" />
                   {/if}
               </td>
           </tr>
           {undef $child_name $node_name $nodeContent $editionAttributeContent $editionStatus}

       {/foreach}
    </table>
</div>

