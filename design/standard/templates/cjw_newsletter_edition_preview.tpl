<div class="context-block">
    {* DESIGN: Header START *}
    <div class="box-header">
        <div class="box-tc">
            <div class="box-ml">
                <div class="box-mr">
                    <div class="box-tl">
                        <div class="box-tr">
                            <h2 class="context-title">{'Newsletter edition preview'|i18n( 'cjw_newsletter/cjw_newsletter_edition_status' )}</h2>
                            {* DESIGN: Subline *}
                            <div class="header-subline">
                            </div>
                            {* DESIGN: Header END *}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {* DESIGN: Content START *}
    <div class="box-bc">
        <div class="box-ml">
            <div class="box-mr">
                <div class="box-bl">
                    <div class="box-br">
                        <div class="box-content">
                            {*def $editionAttributeContent = $editionAttribute.content
                            $editionStatus = $editionAttributeContent.status*}
                            {* show nl preview if standard ez content preview is active *}
                            {def $show_iframes = true()}
                            {if ezpreference( 'admin_navigation_content' )|eq(0)}
                            {set $show_iframes = false()}
                            {/if}
                            <div class="mainobject-window">
                                {* show iframes or icon list *}
                                {include uri="design:includes/cjwnewsletteredition_preview.tpl" newsletter_edition_attribute=$newsletter_edition_attribute show_iframes=$show_iframes height="100"}
                                <div class="break">
                                </div>{* Terminate overflow bug fix *}
                            </div>
                            {undef $show_iframes}
                            {* display preview links *}
                            {*include uri="design:includes/cjwnewsletteredition_preview.tpl" newsletter_edition_attribute=$editionAttribute show_iframes=false()*}
                            {* DESIGN: Content END 1.DIV*}
                        </div>
                        {*
                        Buttonbar for newsletter preview  window.
                        *}
                        <div class="controlbar">
                            {* DESIGN: Control bar START *}
                            <div class="box-bc">
                                <div class="box-ml">
                                    <div class="box-mr">
                                        <div class="box-tc">
                                            <div class="box-bl">
                                                <div class="box-br">
                                                    <form action={'newsletter/send'|ezurl()}  method="post">
                                                        <input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" /><input type="hidden" name="ContentNodeID" value="{$node.node_id}" /><input type="hidden" name="ContentObjectID" value="{$node.object.id}" /><input type="hidden" name="mail_newsletter" value="true">
                                                        <div class="block">
                                                            <div class="left">

                                                                {if $node.data_map.newsletter_edition.content.is_draft}
                                                                    <input class="button" type="submit" name="SendNewsletterButton" value="{"Send Newsletter"|i18n("cjw_newsletter/send")}" />
                                                                {/if}

                                                            </div>
                                                            <div class="right">
                                                            </div>
                                                            <div class="break">
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {* DESIGN: Control bar END *}
                        </div>
                        {* controlbar ends*}
                        {* DESIGN: Content END *}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
