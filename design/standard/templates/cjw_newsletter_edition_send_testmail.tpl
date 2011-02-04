<div class="context-block">

<form action={'newsletter/send'|ezurl()} method="post">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{"Send Test Newsletter at"|i18n("cjw_newsletter/send")}: 'test1@example.com;test2@example.com'</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">


{def $email_receiver_test = $newsletter_list_attribute_content.email_receiver_test}

<div class="mainobject-window">

    {* Testemail input*}
    <input class="box" type="text" name="EmailReseiverTestInput" value="{$email_receiver_test}" />

    <div class="break"></div>{* Terminate overflow bug fix *}
</div>



{* DESIGN: Content END 1.DIV*}</div>


{*
    Buttonbar for newsletter preview  window.
*}
<div class="controlbar">

    {* DESIGN: Control bar START *}
    <div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

        <input type="hidden" name="TopLevelNode" value="{$node.object.main_node_id}" />
        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input type="hidden" name="mail_newsletter" value="true" />

        <div class="block">
            <div class="left">

                {* Newsletter test email button. *}
                <input type="submit" class="button" name="SendNewsletterTestButton" value="{"Send Test Newsletter"|i18n("cjw_newsletter/send")}" />

            </div>

            {* Custom content action buttons. *}
            <div class="right"></div>

            <div class="break"></div>
        </div>

    </div></div></div></div></div></div>
    {* DESIGN: Control bar END *}

</div>
{* controlbar ends*}

{* DESIGN: Content END *}</div></div></div></div></div>
</form>
</div>