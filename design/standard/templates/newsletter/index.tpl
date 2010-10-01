{def $newsletter_root_node_id = ezini( 'NewsletterSettings', 'RootFolderNodeId', 'cjw_newsletter.ini' )}
{def $page_uri = 'newsletter/index'
     $limit = 10}

<div class="newsletter newsletter-index">

    <div class="context-block">

        {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

            <h1 class="context-title">{'Newsletter dashboard'|i18n( 'cjw_newsletter/index' )}</a></h1>

            {* DESIGN: Mainline *}<div class="header-mainline"></div>

        {* DESIGN: Header END *}</div></div></div></div></div></div>

        {* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">





    {* Newsletter Sysem Boxen *}


    {def $newsletter_system_node_list = fetch('content', 'tree',
                                                hash( 'parent_node_id', $newsletter_root_node_id,
                                                      'class_filter_type', 'include',
                                                      'class_filter_array', array( 'cjw_newsletter_system' ),
                                                      'sort_by', array( 'name', true() ),
                                                     ))}
    {foreach $newsletter_system_node_list as $newsletter_system_node}
        {include uri='design:newsletter/index_newsletter_system_info_box.tpl'
                 name='NlSystemBox'
                 newsletter_system_node=$newsletter_system_node}
    {/foreach}

    {undef $newsletter_system_node_list}


        </div></div></div></div></div></div>

    </div>


    {* last actions *}

    {def $last_edition_node_list = fetch( 'content', 'tree',
                                                            hash( 'parent_node_id', $newsletter_root_node_id,
                                                                  'class_filter_type', 'include',
                                                                  'class_filter_array', array( 'cjw_newsletter_edition' ),
                                                                  'limit', $limit,
                                                                  'offset', $view_parameters.offset,
                                                                  'sort_by', array( 'modified', false() )
                                                                 ) )
         $last_edition_node_list_count = fetch( 'content', 'tree_count',
                                                            hash( 'parent_node_id', $newsletter_root_node_id,
                                                                  'class_filter_type', 'include',
                                                                  'class_filter_array', array( 'cjw_newsletter_edition' )
                                                                 ) )}

    <div class="content-view-children">

        <div class="context-block">

            {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

                <h2 class="context-title">{'Last actions'|i18n( 'cjw_newsletter/index' )}</a></h2>

                {* DESIGN: Mainline <div class="header-mainline"></div>*}

            {* DESIGN: Header END *}</div></div></div></div></div></div>

            {* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">


            {include uri = 'design:includes/cjwnewsletteredition_statistic_list.tpl'
                     name = 'EditionList'
                     edition_node_list = $last_edition_node_list
                     edition_node_list_count = $last_edition_node_list_count
                     show_actions_colum = false()}

                <div class="context-toolbar subitems-context-toolbar">
                    {include    name = 'Navigator'
                                uri = 'design:navigator/google.tpl'
                                page_uri = $page_uri
                                item_count = $last_edition_node_list_count
                                view_parameters = $view_parameters
                                item_limit = $limit}

                </div>

            </div></div></div></div></div></div>

        </div>
    </div>


</div>

