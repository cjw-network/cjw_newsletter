{*

    default tpl for a filtertype form view

*}
{def $filter_identifier = $filtertype_object.identifier
     $filter_name = $filtertype_object.name
     $values_available = $filtertype_object.values_available
     $values_selected = $filtertype_object.values
     $operations_available = $filtertype_object.operations_available
     $operation_selected = $filtertype_object.operation
      }

<tr class="filter" id="tr_filter_{$filter_index}">
    <td style="width: 50px;">
        {*<input type="checkbox" name="F[{$filter_index}][a]" value="1" id="active_{$filter_index}">*}

        {*<input type="checkbox" name="{$post_var_remove}" value="{$filter_index}" />*}
        {$filter_index|sum('1')|wash()}

    </td>
    <td style="width: 200px;">
        {$filter_name|wash()}
    </td>
    <td style="width: 150px;">

        {$operations_available[ $operation_selected ]|wash()}

    </td>
    <td>
        {def $counter = 0}
        {foreach $values_selected as $value}
            {if $counter|ne( 0 )} Oder {/if} {$value|wash()}
            {set $counter = $count|inc()}
        {/foreach}
    </td>
</tr>

{undef $identifier}
