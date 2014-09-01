{* newsletter/filtertype/viewbuilder.tpl

    form builder for available Filters

requries filtertype_object_array_active


   @see /extension/cjw_newsletter/design/standard/templates/newsletter/subscription_list_virtual.tpl
  {def $virtual_list_object = $node.data_map.newsletter_list.content)

    {include uri = "design:newsletter/filtertype/viewbuilder.tpl"
             filtertype_object_array_active = $virtual_list_object.filtertypes_active
             filtertype_object_array_available = $virtual_list_object.filtertypes_available}

    {undef $virtual_list_object}
*}

{*$filtertype_object_array_available|attribute(show)*}


<fieldset title="Filter" id="filter">
    <legend>Aktive Filter</legend>

    <table width="100%"><tr><td>
        <table border="0">

            {foreach $filtertype_object_array_active as $index => $filtertype}
                {*
                    - identifier
                    - name
                    - operation
                    - values
                *}

           {* {$filtertype.identifier} ({$index})*}


               {include uri="design:newsletter/filtertype/view/tableline.tpl"
                        filtertype_object = $filtertype
                        filter_index = $index
                        name="CjwNewsletterFilterType"}

            {/foreach}


        </table>
        </td></tr>
    </table>

</fieldset>


