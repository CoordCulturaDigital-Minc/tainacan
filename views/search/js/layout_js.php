<script type="text/javascript">
    var src = $('#src').val();

    $(function () {
        change_breadcrumbs_title('<?php _e('Layout','tainacan') ?>');

        get_colorScheme();

        var layoutOptions = {
            change: function(event, ui) {
                var curr_id = event.target.id;
                var pickedColor = $("#" + curr_id).val();
                if (curr_id == "primary-custom-color") {
                    $('#tainacan-mini .color1').css('background', pickedColor);
                } else if(curr_id == "second-custom-color") {
                    $('#tainacan-mini .color2').css('background', pickedColor);
                }
            }
        };
        $('#primary-custom-color').wpColorPicker(layoutOptions);
        $('#second-custom-color').wpColorPicker(layoutOptions);

        $("#collection-colorset").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: src + "/controllers/collection/collection_controller.php",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false
            }).done(function(r){
                var el = $.parseJSON(r);
            });
        });

        if( window.location.search.indexOf('open_wizard') > -1 ) {
            $("#collection-steps").show();
            var stateObj = {clear: "ok"};
            history.replaceState(stateObj, "tainacan", '?');
        } else {
            $("#collection-steps").hide();
        }

        $("#button_save_and_next").click(function(){
            $("#submit_ordenation_form").submit();
        });

        var selected_view_mode = $('.selected_view_mode').val();
        $("#collection_list_mode").val(selected_view_mode);

        list_ordenation();
        // list_properties_data_ordenation();
        // list_properties_data_selected_ordenation();


        $('#form_ordenation_search').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: src + '/controllers/search/search_controller.php',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false
            }).done(function (result) {
                $("#collection-colorset").submit();
                elem = jQuery.parseJSON(result);
                showAlertGeneral(elem.title, elem.msg, elem.type);
                $("#tainacan-breadcrumbs .collection-title").click();
            });
        });
    });

    $("#layout-accordion").accordion({
        collapsible: true,
        header: "h3",
        animate: 200,
        heightStyle: "content"
        // icons: false
    });
    $('#layout-accordion .ui-accordion-content').show();

    function get_colorScheme() {
        var coll_id = $('#collection_id').val();
        $.ajax({
            type: "POST",
            url: src + "/controllers/collection/collection_controller.php",
            data: {operation: 'get_color_scheme', collection_id: coll_id }
        }).done(function(r){
            var el = $.parseJSON(r);
            $('#tainacan-mini .color1').css('background', el.primary_color);
            $('#tainacan-mini .color2').css('background', el.secondary_color);
        });
    }

    function colorize(color) {
        var cor1 = $('.' + color + ' .color1').val();
        var cor2 = $('.' + color + ' .color2').val();

        $('#tainacan-mini .color1').css('background', cor1);
        $('#tainacan-mini .color2').css('background', cor2);
        $('a.wp-color-result').first().css('background', cor1);
        $('a.wp-color-result').last().css('background', cor2);
        $("#primary-custom-color").val(cor1);
        $("#second-custom-color").val(cor2);
    }

    function list_properties_data_ordenation(){
        $.ajax({
            type: "POST",
            url: $('#src').val() + "/controllers/property/property_controller.php",
            data: {collection_id: $('#collection_id').val(), operation: 'list_property_data', category_id: $('#property_category_id').val()}
        }).done(function (result) {
            elem = jQuery.parseJSON(result);
            if (elem.no_properties !== true) {
                $('#collection_order_properties').html('');
                $.each(elem.property_data, function (idx, property) {
                    //if(!property.metas.is_repository_property&&property.metas.socialdb_property_created_category==elem.category.term_id){
                    //if(property.metas.is_repository_property||property.metas.socialdb_property_created_category==elem.category.term_id){
                    //if(!property.metas.is_repository_property){
                    $('#collection_order_properties').append('<option value="'+property.id+'">' + property.name + ' (<?php _e('Type','tainacan') ?>:'+property.type+')</option>');
                    //}
                });
            }
        });
    }

    function list_ordenation() {
        $.ajax({
            url: $('#src').val() + '/controllers/collection/collection_controller.php',
            type: 'POST',
            data: {operation: 'list_ordenation', collection_id: $("#collection_id").val()}
        }).done(function (result) {
            elem = jQuery.parseJSON(result);

            if (elem.general_ordenation) {
                $("#collection_order").append("<optgroup label='<?php _e('General ordenation','tainacan') ?>'>");
                $.each(elem.general_ordenation, function (idx, general) {
                    if (general && general !== false) {
                        $("#collection_order").append("<option value='" + general.id + "' selected='selected' >" + general.name + "</option>");
                    }
                });
            }
            if (elem.property_data) {
                cl(elem.property_data);
                $("#collection_order").append("<optgroup label='<?php _e('Data properties','tainacan') ?>'>");
                $.each(elem.property_data, function (idx, data) {
                    if (data && data !== false) {
                        $("#collection_order").append("<option value='" + data.id + "' selected='selected' >" + data.name + " - ( <?php _e('Type','tainacan') ?>:"+data.type+" ) </option>");
                    }
                });
            }
            if (elem.rankings) {
                $("#collection_order").append("<optgroup label='<?php _e('Rankings','tainacan') ?>'>");
                $.each(elem.rankings, function (idx, ranking) {
                    if (ranking && ranking !== false) {
                        $("#collection_order").append("<option value='" + ranking.id + "' selected='selected' >" + ranking.name + "  - ( <?php _e('Type','tainacan') ?>:"+ranking.type+" ) </option>");
                    }
                });
            }
            if (elem.selected) {
                $("#collection_order").val(elem.selected);
            }
            $('.dropdown-toggle').dropdown();
        });
    }

    function list_properties_data_selected_ordenation(){
        $.ajax({
            type: "POST",
            url: $('#src').val() + "/controllers/property/property_controller.php",
            data: {collection_id: $('#collection_id').val(), operation: 'list_property_data', category_id: $('#property_category_id').val()}
        }).done(function (result) {
            elem = jQuery.parseJSON(result);
            if (elem.no_properties !== true) {
                $('#collection_order_selected_properties').html('');
                $.each(elem.property_data, function (idx, property) {
                    // $("#collection_order").append("<option value='" + data.id + "' selected='selected' >" + data.name + " - ( <?php _e('Type','tainacan') ?>:"+data.type+" ) </option>");
                    // if(property.metas.socialdb_property_data_column_ordenation&&property.metas.socialdb_property_data_column_ordenation==='true'){
                    $('#collection_order').append('<option selected="selected" value="'+property.id+'">' + property.name + ' (<?php _e('Type','tainacan') ?>:'+property.type+')</option>');
                });
            } else {
                $('#collection_order_selected_properties').html('');
                $('#collection_order_selected_properties').append('<option value="">' + '<?php _e('No data properties inserted','tainacan') ?>' + '</option>');
            }

        });
    }

    function renumber_all() {
        // renumber_table_horizontal('#table_search_data_id');
        renumber_table_left('#table_search_data_left_column_id');
        // renumber_table_right('#table_search_data_right_column_id');
    }

    function save_widget_tree(tree_type) {
        $.ajax({
            type: "POST",
            url: $('#src').val() + "/controllers/search/search_controller.php",
            data: {
                collection_id: $('#collection_id').val(),
                tree_type: $(tree_type).val(),
                operation: 'save_default_widget_tree'}
        }).done(function (result) {
            elem = jQuery.parseJSON(result);
            showAlertGeneral(elem.title, elem.msg, elem.type);
        });
    }

    function remove_property_ordenation(e){
        if($(e).val()){
            $.ajax({
                type: "POST",
                url: $('#src').val() + "/controllers/search/search_controller.php",
                data: {
                    collection_id: $('#collection_id').val(),
                    property_id: $(e).val(),
                    operation: 'remove_property_ordenation'}
            }).done(function (result) {
                $('#collection_order').html('');
                list_ordenation();
                list_properties_data_ordenation();
                list_properties_data_selected_ordenation();
                elem = jQuery.parseJSON(result);
                showAlertGeneral(elem.title, elem.msg, elem.type);
            });
        }
    }

/*
    function add_property_ordenation(){
        if($('#collection_order_properties').val()){
            $.ajax({
                type: "POST",
                url: $('#src').val() + "/controllers/search/search_controller.php",
                data: {
                    collection_id: $('#collection_id').val(),
                    property_id: $('#collection_order_properties').val(),
                    operation: 'add_property_ordenation'}
            }).done(function (result) {
                $('#collection_order').html('');
                list_ordenation();
                list_properties_data_ordenation();
                list_properties_data_selected_ordenation();
                elem = jQuery.parseJSON(result);
                showAlertGeneral(elem.title, elem.msg, elem.type);
            });
        }
    }

*/

    function showOrientationStyles() {
        var orientation_class = $("#search_data_orientation option:selected").attr('class');
        $("#select_menu_style option").each(function(idx, el){
            var item_classes = $(el).attr('class');
            var filter = "";
            if ( null !== (item_classes.match(" ")) ) {
                filter = item_classes.split(" ")[0];
            } else {
                filter = item_classes;
            }

            if ( orientation_class.indexOf(filter) > -1 ) {
                $(el).removeClass('hide-el');
            } else {
                $(el).addClass('hide-el');
                $('.select2-menu').change();
            }
        });
    }
</script>
