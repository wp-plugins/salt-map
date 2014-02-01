Array.prototype.remove = function(id) {
	var index = -1;
	for (var i = 0; i < this.length; i++) {
		if(this[i].id === id) {
			index = i;
			break;
		}
	}

	if(index === -1) {
		return;
	}
	
	var rest = this.slice(index + 1 || this.length);
	this.length = index < 0 ? this.length + index : index;
	return this.push.apply(this, rest);
};

function go() {
	var locationFields = [];
	var displayContainer = document.getElementById("display_container");
	var fieldTemplate = document.getElementById("field_template").innerHTML;
	var fieldData = document.getElementById("salt_map_settings_fieldsSettings");
	var NewRemover = function(id) {
		return function (e) {
			e.preventDefault();
			locationFields.remove(id);
			displayContainer.removeChild(document.getElementById(id));
			fieldData.value = JSON.stringify(locationFields);
		}
    }
    
    var add = function(field) {
    	var text = Mustache.render(fieldTemplate, field);
		var div = document.createElement("div");
		div.id = field.id;
		div.innerHTML = text;
		var removeButton = div.getElementsByTagName("button")[0];
		removeButton.onclick = NewRemover(field.id);
		displayContainer.appendChild(div);
		locationFields.push(field);
    }

    var clearFields = function(){
		var fields = document.getElementById("field_data").querySelectorAll("input");
		for (var i = 0; i < fields.length; i++) {
			fields[i].value = "";
		}
	}
	
    var jsonFieldData = [];
    if(fieldData.value !== "") {    	
    	var jsonFieldData = JSON.parse(fieldData.value);
    }
	for(var i = 0; i < jsonFieldData.length; i++) {
		add(jsonFieldData[i]);
	}
	
	var addButton = document.getElementById("add_button");
	addButton.onclick = function(e) {
		e.preventDefault();
		var fields = document.getElementById("field_data").querySelectorAll("input, select");
		var field = {};
		for (var i = 0; i < fields.length; i++) {
			if (fields[i].id === "options") {
				field[fields[i].id] = fields[i].value.split(/[ ,]+/);
			} else {
				field[fields[i].id] = fields[i].value;				
			}
		}
		var fieldTemplate = document.getElementById("field_template").innerHTML;
		add(field);
		fieldData.value = JSON.stringify(locationFields);
		clearFields();
	}

	var clearButton = document.getElementById("clear_button");
	clearButton.onclick = function (e) {
		e.preventDefault();
		clearFields();
	}
	
	var typeSelect = document.getElementById("type");
	typeSelect.onchange = function (e) {
		var options = document.getElementById("options_field");
		if (typeSelect.value === "text") {
			options.style.display = "none";
		} else if (typeSelect.value === "select") {
			options.style.display = "block";
		}
	} 
}



jQuery(document).ready(function() {
 
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  jQuery('.uploadButton').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = jQuery(this);
    var id = button.attr('for');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
        jQuery("#" + id).val(attachment.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });

  jQuery('.add_media').on('click', function(){
    _custom_media = false;
  });

 
});