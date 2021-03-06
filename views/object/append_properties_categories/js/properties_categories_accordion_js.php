<script>
    $(function () {
        var src = $('#src').val();
       // var properties_autocomplete = edit_get_val($("#edit_properties_autocomplete").val());
       // autocomplete_edit_item_property_data(properties_autocomplete); 
        $('[data-toggle="tooltip"]').tooltip();
        pc_list_properties_term_insert_objects();
    });
    function autocomplete_object_property_edit(property_id, object_id) {
        $("#autocomplete_value_" + property_id + "_" + object_id).autocomplete({
            source: $('#src').val() + '/controllers/object/object_controller.php?operation=get_objects_by_property_json&property_id=' + property_id,
            messages: {
                noResults: '',
                results: function () {
                }
            },
            minLength: 2,
            select: function (event, ui) {
                $("#autocomplete_value_" + property_id + "_" + object_id).html('');
                $("#autocomplete_value_" + property_id + "_" + object_id).val('');
                //var temp = $("#chosen-selected2 [value='" + ui.item.value + "']").val();
                var temp = $("#property_value_" + property_id + "_" + object_id + " [value='" + ui.item.value + "']").val();
                if (typeof temp == "undefined") {
                     var already_selected = false;
                     //validacao do campo
                    $('#core_validation_'+property_id).val('true');
                    set_field_valid(property_id,'core_validation_'+property_id);
                    //fim validacao do campo
                    $("#property_value_" + property_id + "_" + object_id+"_edit option").each(function(){
                        if($(this).val()==ui.item.value){
                            already_selected = true;
                        }
                    });
                    if(!already_selected){
                        if($('#cardinality_'+property_id + "_" + object_id).val()=='1'){
                             $("#property_value_" + property_id + "_" + object_id + "_edit").html('');
                        }
                        $("#property_value_" + property_id + "_" + object_id+"_edit").append("<option class='selected' value='" + ui.item.value + "' selected='selected' >" + ui.item.label + "</option>");
                        //hook para validacao do campo ao selecionar
                        if(Hook.is_register( 'tainacan_validate_cardinality_onselect')){
                            Hook.call( 'tainacan_validate_cardinality_onselect', [ 'select[name="socialdb_property_'+property_id+'[]"]',property_id ] );
                        }
                    }
                }
                setTimeout(function () {
                    $("#autocomplete_value_" + property_id + "_" + object_id).val('');
                }, 100);
            }
        });
    }
    
     /**
     * Autocomplete para os metadados de dados para insercao/edicao de item unico
     * @param {type} e
     * @returns {undefined}
     */
    function autocomplete_edit_item_property_data(properties_autocomplete) {
         if (properties_autocomplete) {
            $.each(properties_autocomplete, function (idx, property_id) {
                        //validate
                         $(".form_autocomplete_value_" + property_id).keyup(function(){
                            var cont = 0;
                            $(".form_autocomplete_value_" + property_id).each(function(index,value){
                               if( $(this).val().trim()!==''){
                                    cont++;
                                }
                            });

                            if( cont===0){
                                $('#core_validation_'+property_id).val('false');
                            }else{
                                 $('#core_validation_'+property_id).val('true');
                            } 

                            set_field_valid(property_id,'core_validation_'+property_id);
                        });
                        $(".form_autocomplete_value_" + property_id).change(function(){
                            var cont = 0;
                            $(".form_autocomplete_value_" + property_id).each(function(index,value){
                               if( $(this).val().trim()!==''){
                                    cont++;
                                }
                            });

                            if( cont===0){
                                $('#core_validation_'+property_id).val('false');
                            }else{
                                 $('#core_validation_'+property_id).val('true');
                            }
                            set_field_valid(property_id,'core_validation_'+property_id);
                        });
                        // end validate
                        $(".form_edit_autocomplete_value_" + property_id).autocomplete({
                            source: $('#src').val() + '/controllers/collection/collection_controller.php?operation=list_items_search_autocomplete&property_id=' + property_id,
                            messages: {
                                noResults: '',
                                results: function () {
                                }
                            },
                            minLength: 2,
                            select: function (event, ui) {
                                $("#form_edit_autocomplete_value_" + property_id).val('');
                                //var temp = $("#chosen-selected2 [value='" + ui.item.value + "']").val();
                                var temp = $("#form_edit_autocomplete_value_" + property_id).val();
                                if (typeof temp == "undefined") {
                                    $("#form_edit_autocomplete_value_" + property_id).val(ui.item.value);
                                }
                            }
                        });
                    });
                }
    }
    
     function clear_select_object_property(e,property_id,object_id) {
        $('option:selected', e).remove();
         $("#property_value_" + property_id + "_" + object_id+"_edit option").each(function()
        {
           $(this).attr('selected','selected');
        });
        //validacao do campo
        var cont = 0;
        $("#property_value_" + property_id + "_" + object_id + "_edit option").each(function ()
        {
            cont++;
        });
        if(cont==0){
            $('#core_validation_'+property_id).val('false');
            set_field_valid(property_id,'core_validation_'+property_id);
        }            
        //fim validacao do campo
        if(Hook.is_register( 'tainacan_validate_cardinality_onselect')){
            Hook.call( 'tainacan_validate_cardinality_onselect', [ 'select[name="socialdb_property_'+property_id+'[]"]',property_id ] );
        }
        //$('.chosen-selected2 option').prop('selected', 'selected');
    }
    
    //************************* properties terms ******************************************//
    function pc_list_properties_term_insert_objects() {
        var categories = edit_get_val($("#pc_categories").val());
        var radios = edit_get_val($("#pc_properties_terms_radio").val());
        var selectboxes = edit_get_val($("#pc_properties_terms_selectbox").val());
        var trees = edit_get_val($("#pc_properties_terms_tree").val());
        var checkboxes = edit_get_val($("pc_#properties_terms_checkbox").val());
        var multipleSelects = edit_get_val($("#pc_properties_terms_multipleselect").val());
        var treecheckboxes = edit_get_val($("#pc_properties_terms_treecheckbox").val());
        pc_list_radios(radios,categories);
        pc_list_tree(trees,categories);
        pc_list_selectboxes(selectboxes,categories);
        pc_list_multipleselectboxes(multipleSelects,categories);
        pc_list_checkboxes(checkboxes,categories);
        pc_list_treecheckboxes(treecheckboxes,categories);
    }
    // radios
    function pc_list_radios(radios,categories) {
        if (radios) {
            $.each(radios, function (idx, radio) {
                $.ajax({
                    url: $('#src').val() + '/controllers/property/property_controller.php',
                    type: 'POST',
                    data: {collection_id: $("#collection_id").val(), operation: 'get_children_property_terms', property_id: radio}
                }).done(function (result) {
                    elem = jQuery.parseJSON(result);
                    $('#field_property_term_' + radio).html('');
                    $.each(elem.children, function (idx, children) {
                        var required = '';
                        var checked = '';
                        if(elem.metas.socialdb_property_required==='true'){
                            required = ' onchange="validate_radio(' + radio + ')"';
                        }
                        //  if (property.id == selected) {
                        //     $('#property_object_reverse').append('<option selected="selected" value="' + property.id + '">' + property.name + ' - (' + property.type + ')</option>');
                        //  } else {
                        if(categories.indexOf(children.term_id)>-1){
                            checked = 'checked="checked"';
                        }
                       //  delete_value(children.term_id);//retiro
                        $('#field_property_term_' + radio).append('<input '+checked+' '+required+' type="radio" name="socialdb_propertyterm_'+radio+'" value="' + children.term_id + '">&nbsp;' + children.name + '<br>');
                        //  }
                    });
                });
            });
        }
    }
    // checkboxes
    function pc_list_checkboxes(checkboxes,categories) {
        if (checkboxes) {
            $.each(checkboxes, function (idx, checkbox) {
                $.ajax({
                    url: $('#src').val() + '/controllers/property/property_controller.php',
                    type: 'POST',
                    data: {collection_id: $("#collection_id").val(), operation: 'get_children_property_terms', property_id: checkbox}
                }).done(function (result) {
                    elem = jQuery.parseJSON(result);
                    $('#field_property_term_' + checkbox).html('');
                    $.each(elem.children, function (idx, children) {
                        var required = '';
                        var checked = '';
                        // delete_value(children.term_id);
                        if(elem.metas.socialdb_property_required==='true'){
                            required = 'onchange="validate_checkbox(' + checkbox + ')"';
                        }
                        if(categories&&categories.indexOf(children.term_id)>-1){
                            checked = 'checked="checked"';
                        }
                        //  if (property.id == selected) {
                        //     $('#property_object_reverse').append('<option selected="selected" value="' + property.id + '">' + property.name + ' - (' + property.type + ')</option>');
                        //  } else {
                        $('#field_property_term_' + checkbox).append('<input '+checked+' '+required+'  type="checkbox" name="socialdb_propertyterm_'+checkbox+'[]" value="' + children.term_id + '">&nbsp;' + children.name + '<br>');
                        //  }
                    });
                });
            });
        }
    }
    // selectboxes
    function pc_list_selectboxes(selectboxes,categories) {
        if (selectboxes) {
            $.each(selectboxes, function (idx, selectbox) {
                //validation
                $('#field_property_term_' + selectbox).select(function(){
                    if( $("#field_property_term_" + selectbox).val()===''){
                        $('#core_validation_'+selectbox).val('false');
                    }else{
                         $('#core_validation_'+selectbox).val('true');
                    }
                    set_field_valid(property_id,'core_validation_'+selectbox);
                });
                //
                $.ajax({
                    url: $('#src').val() + '/controllers/property/property_controller.php',
                    type: 'POST',
                    data: {collection_id: $("#collection_id").val(), operation: 'get_children_property_terms', property_id: selectbox}
                }).done(function (result) {
                    elem = jQuery.parseJSON(result);
                    $('#field_property_term_' + selectbox).html('');
                    $('#field_property_term_' + selectbox).append('<option value=""><?php _e('Select','tainacan') ?>...</option>');
                    $.each(elem.children, function (idx, children) {
                        var checked = '';
                       //  delete_value(children.term_id);
                        if(categories&&categories.indexOf(children.term_id)>-1){
                            checked = 'selected="selected"';
                        }
                        $('#field_property_term_' + selectbox).append('<option '+checked+' value="' + children.term_id + '">' + children.name + '</option>');
                        //  }
                    });
                });
            });
        }
    }
     // multiple
    function pc_list_multipleselectboxes(multipleSelects,categories) {
        if (multipleSelects) {
            $.each(multipleSelects, function (idx, multipleSelect) {
                //validation
                $('#field_property_term_' + multipleSelect).select(function(){
                    if( $("#field_property_term_" + multipleSelects).val()===''){
                        $('#core_validation_'+multipleSelect).val('false');
                    }else{
                         $('#core_validation_'+multipleSelect).val('true');
                    }
                    set_field_valid(multipleSelect,'core_validation_'+multipleSelect);
                });
                //init
                $.ajax({
                    url: $('#src').val() + '/controllers/property/property_controller.php',
                    type: 'POST',
                    data: {collection_id: $("#collection_id").val(), operation: 'get_children_property_terms', property_id: multipleSelect}
                }).done(function (result) {
                    elem = jQuery.parseJSON(result);
                    $('#field_property_term_' + multipleSelect).html('');
                    $.each(elem.children, function (idx, children) {
                        var checked = '';
                       // delete_value(children.term_id);
                        if(categories.indexOf(children.term_id)>-1){
                            checked = 'selected="selected"';
                        }
                        $('#field_property_term_' + multipleSelect).append('<option '+checked+' value="' + children.term_id + '">' + children.name + '</option>');
                        //  }
                    });
                });
            });
        }
    }
    // treecheckboxes
    function pc_list_treecheckboxes(treecheckboxes,categories) {
        if (treecheckboxes) {
            $.each(treecheckboxes, function (idx, treecheckbox) {
                $("#field_property_term_"+treecheckbox).dynatree({
                    selectionVisible: true, // Make sure, selected nodes are visible (expanded).  
                    checkbox: true,
                    initAjax: {
                        url: $('#src').val() + '/controllers/category/category_controller.php',
                        data: {
                            collection_id: $("#collection_id").val(),
                            property_id: treecheckbox,
                            order: 'name',
                            operation: 'initDynatreeDynamic'
                        }
                        , addActiveKey: true
                    },
                    onLazyRead: function (node) {
                        node.appendAjax({
                             url: $('#src').val() + '/controllers/collection/collection_controller.php',
                            data: {
                                collection: $("#collection_id").val(),
                                key: node.data.key,
                                classCss: node.data.addClass,
                                order: 'name',
                                //operation: 'findDynatreeChild'
                                operation: 'expand_dynatree'
                            }
                        });
                    },
                    onClick: function (node, event) {
                        // Close menu on click
                        //delete_value(node.data.key);
                        $("#property_object_category_id").val(node.data.key);
                        $("#property_object_category_name").val(node.data.title);

                    },
                    onCreate: function (node, span) {
                        $("#field_property_term_"+treecheckbox).dynatree("getRoot").visit(function(node){
                           // delete_value(node.data.key);
                           if(categories.indexOf(node.data.key)>-1){
                                node.select();
                            }
                        });
                         bindContextMenuSingle(span,'field_property_term_' + treecheckbox);
                    },
                    onSelect: function (flag, node) {
                        var cont = 0;
                        var selKeys = $.map(node.tree.getSelectedNodes(), function (node) {
                            return node;
                        });
                        $("#socialdb_propertyterm_" + treecheckbox).html('');
                        $.each(selKeys, function (index, key) {
                            cont++;
                            $("#socialdb_propertyterm_" + treecheckbox).append('<input type="hidden" name="socialdb_propertyterm_'+treecheckbox+'[]" value="' + key.data.key + '" >');
                        });
                        if(cont===0){
                            $('#core_validation_'+treecheckbox).val('false');
                            set_field_valid(treecheckbox,'core_validation_'+treecheckbox);
                         }else{
                            $('#core_validation_'+treecheckbox).val('true');
                            set_field_valid(treecheckbox,'core_validation_'+treecheckbox); 
                         }
                    }
                });
            });
        }
    }
    
    // tree
    function pc_list_tree(trees,categories) {
        if (trees) {
            $.each(trees, function (idx, tree) {
                $("#field_property_term_"+tree).dynatree({
                    checkbox: true,
                    // Override class name for checkbox icon:
                    classNames: {checkbox: "dynatree-radio"},
                    selectMode: 1,
                    selectionVisible: true, // Make sure, selected nodes are visible (expanded). 
                    checkbox: true,
                      initAjax: {
                        url: $('#src').val() + '/controllers/category/category_controller.php',
                        data: {
                            collection_id: $("#collection_id").val(),
                            property_id: tree,
                           // hide_checkbox: 'true',
                            order: 'name',
                            operation: 'initDynatreeDynamic'
                        }
                        , addActiveKey: true
                    },
                    onLazyRead: function (node) {
                        node.appendAjax({
                            url: $('#src').val() + '/controllers/collection/collection_controller.php',
                            data: {
                                collection: $("#collection_id").val(),
                                key: node.data.key,
                                //hide_checkbox: 'true',
                                classCss: node.data.addClass,
                                 order: 'name',
                                //operation: 'findDynatreeChild'
                                operation: 'expand_dynatree'
                            }
                        });
                    },
                    onCreate: function (node, span) {
                         $("#field_property_term_"+tree).dynatree("getRoot").visit(function(node){
                          // delete_value(node.data.key); 
                           if(categories.indexOf(node.data.key)>-1){
                                node.select();
                            }
                        });
                        bindContextMenuSingle(span,'field_property_term_' + tree);
                    },
                    onSelect: function (flag, node) {
                        if ($("#socialdb_propertyterm_" + tree).val() === node.data.key) {
                            $("#socialdb_propertyterm_" + tree).val("");
                             $('#core_validation_'+tree).val('false');
                             set_field_valid(tree,'core_validation_'+tree);
                        } else {
                            $("#socialdb_propertyterm_" + tree).val(node.data.key);
                            $('#core_validation_'+tree).val('true');
                             set_field_valid(tree,'core_validation_'+tree);
                        }
                    }
                });
            });
        }
    }
    
    
    
    // get value of the property
    function edit_get_val(value) {
        if (!value||value === '' ) {
            return false;
        } else if (value.split(',')[0] === '' && value !== '') {
            return [value];
        } else {
            return value.split(',');
        }
    }
    
    function delete_value(category_id){
       var classifications =   $("#object_classifications_edit").val().split(',');
       if(classifications.length>0&&category_id){
           var index = classifications.indexOf(category_id);
           if(index>-1){
               classifications.splice(index, 1);
               $("#object_classifications_edit").val(classifications.join());
           }
       }
    }
</script>
