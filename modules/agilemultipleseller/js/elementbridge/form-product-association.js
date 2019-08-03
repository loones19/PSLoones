/**
* Subset of following file
       \adminDEV\themes\default\js\bundle\product\form.js
*/

$(document).ready(function () {
	form.init();
	relatedProduct.init();
	displayFormCategory.init();
	defaultCategory.init();
	formCategory.init();
	BOEvent.emitEvent("Product Categories Management started", "CustomEvent");

	$("li").removeClass("card"); /* adjust related products item layout  */
	$("div").removeClass("radio"); /* adjust Default category radio button layour */
	$("#form_step1_new_category_save").hide(); /* remove Save button of New Category */
});


/**
 * Related product management
 */
var relatedProduct = (function () {
    var parentElem = $('#related-product');

    return {
        'init': function () {
            /** Click event on the add button */
            parentElem.find('a.open').on('click', function (e) {
                e.preventDefault();
                parentElem.find('#related-content').removeClass('hide');
                $(this).hide();
            });
        }
    };
})();


/**
 * Display category form management
 */
var displayFormCategory = (function() {
	var parentElem = $('#add-categories');
	return {
		'init': function() {
			/** Click event on the add button */
			parentElem.find('a.open').on('click', function(e) {
				e.preventDefault();
				parentElem.find('#add-categories-content').removeClass('hide');
				$(this).hide();
			});
		}
	};
})();


/**
 * Form category management
 */
var formCategory = (function() {
	var elem = $('#form_step1_new_category');

	/** Send category form and it to nested categories */
	function send(){
		$.ajax({
			type: 'POST',
			url: elem.attr('data-action'),
			data: {
				'form[category][name]': $('#form_step1_new_category_name').val(),
				'form[category][id_parent]': $('#form_step1_new_category_id_parent').val(),
				'form[_token]': $('#form #form__token').val()
			},
			beforeSend: function() {
				$('button.submit', elem).attr('disabled', 'disabled');
				$('ul.text-danger', elem).remove();
				$('*.has-danger', elem).removeClass('has-danger');
				$('*.has-danger').removeClass('has-danger');
			},
			success: function(response){
				//inject new category into category tree
				var html = '<li><div class="checkbox"><label><input type="checkbox" name="form[step1][categories][tree][]" value="'+response.category.id+'">'+response.category.name[1]+'</label></div></li>';
				var parentElement = $('#form_step1_categories input[value='+response.category.id_parent+']').parent().parent();
				if(parentElement.next('ul').length === 0){
					html = '<ul>' + html + '</ul>';
					parentElement.append(html);
				}else{
					parentElement.next('ul').append(html);
				}

				//inject new category in parent category selector
				$('#form_step1_new_category_id_parent').append('<option value="' + response.category.id + '">' + response.category.name[1] + '</option>');
			},
			error: function(response){
				$.each(jQuery.parseJSON(response.responseText), function(key, errors){
					var html = '<ul class="list-unstyled text-danger">';
					$.each(errors, function(key, error){
						html += '<li>' + error + '</li>';
					});
					html += '</ul>';

					$('#form_step1_new_'+key).parent().append(html);
					$('#form_step1_new_'+key).parent().addClass('has-danger');
				});
			},
			complete: function(){
				$('#form_step1_new_category button.submit').removeAttr('disabled');
			}
		});
	}

	return {
		'init': function() {
			/** remove all categories from selector, except pre defined */
			elem.find('button.submit').click(function(){
				send();
			});
		}
	};
})();

/**
 * Navigation management
 */
var nav = (function() {
	return {
		'init': function() {
			/** Manage tabls hash routes */
			var hash = document.location.hash;
			var formNav = $("#form-nav");
			var prefix = 'tab-';
			if (hash) {
				formNav.find("a[href='" + hash.replace(prefix,'') + "']").tab('show');
			}

			formNav.find("a").on('shown.bs.tab', function (e) {
				if(e.target.hash) {
					onTabSwitch(e.target.hash);
					window.location.hash = e.target.hash.replace('#', '#' + prefix);
				}
			});

			/** on tab switch */
			function onTabSwitch(currentTab){
				if (currentTab === '#step2'){
					/** each switch to price tab, reload combinations into specific price form */
					specificPrices.refreshCombinationsList();
				}
			}
		}
	};
})();


