{* newsletter/filtertype/formbuilder.tpl

    form builder for available Filters

requries filtertype_object_array_active

*}

{*$filtertype_object_array_available|attribute(show)*}


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

<script language="JavaScript">
{literal}
function toggleMultiSelect( field )
{
    select = $( '#values_' + field );
    if ( select.attr( 'multiple' ) == true )
    {
        select.attr( 'multiple', false );
    }
    else
    {
        select.attr( 'multiple', true );
    }
}

function addTextValue( field )
{
    select = $( '#text_values_' + field );
    alert( 'TODO insert new text input field' );
}

{/literal}
</script>

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


               {include uri="design:newsletter/filtertype/form/tableline.tpl"
                        filtertype_object = $filtertype
                        filter_index = $index
                        name="CjwNewsletterFilterType"
                        post_var_remove=$post_var_remove}

            {/foreach}


        </table>
        </td></tr>
    </table>

</fieldset>


</form>

