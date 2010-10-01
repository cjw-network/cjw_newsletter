{* Newsletter - subscription_list_csvexport *}


{def $limit = 50
     $base_uri = concat( 'newsletter/subscription_list_csvexport/', $list_node.node_id )}

<div class="newsletter newsletter-subscription_list_csvexport">

<form enctype="multipart/form-data" name="subscription_csvexport" method="post" action={$base_uri|ezurl}>

    <div class="context-block">
        {* DESIGN: Header START *}
        <div class="box-header">
            <div class="box-tc">
                <div class="box-ml">
                    <div class="box-mr">
                        <div class="box-tl">
                            <div class="box-tr">
                                <h1 class="context-title">{"Subscription CSV export"|i18n( 'cjw_newsletter/subscription_list_csvexport' )}</h1>
                                {* DESIGN: Mainline *}
                                <div class="header-mainline">
                                </div>

                                {if is_set($warning)}
                                <div class="message-warning">
                                    <h2>{$warning|wash}</h2>
                                </div>
                                {/if}

                                {* DESIGN: Header END *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {* DESIGN: Content START *}
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-content">
                    <div class="context-attributes">
                        <div class="block float-break">

                        </div>

                        {* show / set csv delimiter *}
                        <div class="block">
                            <label>
                                {'CSV field delimiter'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}: <input style="text-align:center" type="text" size="1" maxlength="1" name="CsvDelimiter" value="{$csv_delimiter}" />
                            </label>
                        </div>

                        {* preview for csv import, data limited *}
                        {if is_set( $str_preview_csv_data )}
                            <div class="block">
                                <label>
                                    {'CSV preview'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}:
                                </label>
                                <pre style="overflow: auto;">{$str_preview_csv_data}</pre>
                            </div>
                        {/if}

                        <div class="break">
                        </div>


                    </div>
                    {* DESIGN: Content END *}
                </div>
                {* Buttons. *}
                <div class="controlbar">
                    {* DESIGN: Control bar START *}
                    <div class="box-bc">
                        <div class="box-ml">
                            <div class="box-mr">
                                <div class="box-tc">
                                    <div class="box-bl">
                                        <div class="box-br">
                                            {* Edit *}
                                            <div class="left">

                                                <input class="button" type="submit" name="ExportButton" value="{'Export'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}" title="{'Export'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}" />
                                                <input class="button" type="submit" name="PreviewButton" value="{'Preview'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}" title="{'Preview'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}" />
                                                <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}" title="{'Cancel subscription export.'|i18n( 'cjw_newsletter/subscription_list_csvexport' )}" />

                                            </div>
                                        </div>{* DESIGN: Control bar END *}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</form>

</div>
