/*! ea.js v1.1| MIT License | github.com/umbokc/emmet-attr */

document.addEventListener('DOMContentLoaded', function(){

// emmet attr
  var nodeList = document.getElementsByTagName('*');

  var emmets = {
    w : 'width',
    h : 'height',
    maw : 'max-width',
    mah : 'max-height',
    miw : 'min-width',
    mih : 'min-height',

    fz : 'font-size',

    p : 'padding',
    pt : 'padding-top',
    pr : 'padding-right',
    pb : 'padding-bottom',
    pl : 'padding-left',

    m : 'margin',
    mt : 'margin-top',
    mr : 'margin-right',
    mb : 'margin-bottom',
    ml : 'margin-left',
    'm-a' : [ 'margin', 'auto' ],
    'mt-a' : [ 'margin-top', 'auto' ],
    'mr-a' : [ 'margin-right', 'auto' ],
    'mb-a' : [ 'margin-bottom', 'auto' ],
    'ml-a' : [ 'margin-left', 'auto' ],

    bg : 'background',
    bgi : 'background-image',
    bgsz : 'background-size',
  };

  var xPathRes = document.evaluate ( "//*[@ea]//*", document.getElementsByTagName('html')[0], null, XPathResult.ORDERED_NODE_ITERATOR_TYPE, null);
  var actualSpan = xPathRes.iterateNext ();
  while (actualSpan) {

    for (var j = 0, m = actualSpan.attributes.length; j < m; j++) {

      // var prefix = 'u-';
      // var prop = emmets[actualSpan.attributes[j].name.replace(prefix, '')];
      var prop = emmets[actualSpan.attributes[j].name];

      // var val = actualSpan.attributes[j].name.split(":");
      var val = actualSpan.attributes[j].value;

      if(prop){
        if(typeof(prop) == "string" && val.trim() != ""){
          actualSpan.style[prop] = val;
        } else if (typeof(prop) == "object") {
          actualSpan.style[prop[0]] = prop[1];
        }
      }

    }

    actualSpan = xPathRes.iterateNext ()
  }

// no-click-js
  document.querySelectorAll('[ea] [no-click-js]').forEach(function(the_link){
    the_link.onclick = function (e){
      e.preventDefault();
    };
  });

  function toggle_class(element, class_name){
    if (element.classList) { 
      element.classList.toggle(class_name);
    } else {
      // For IE9
      var classes = element.className.split(" ");
      var i = classes.indexOf(class_name);

      if (i >= 0) 
        classes.splice(i, 1);
      else 
        classes.push(class_name);
      element.className = classes.join(" "); 
    }
  }

  document.querySelectorAll('[ea] [toggle-class-name]').forEach(function(item){
    item.onclick = function (e){
      e.preventDefault();
      cl = item.getAttribute('toggle-class-name');
      document.querySelectorAll(item.getAttribute('toggle-class-to')).forEach(function(the_item){
        toggle_class(the_item, cl);
      });
    };
  });

// tabs
  document.querySelectorAll('[ea] [ea-tabs]').forEach(function(wrap){
    var name_tabs = wrap.getAttribute('ea-tabs');
    var tabs = wrap.querySelectorAll('[tab-name=' + name_tabs + ']');
    var tab_active = wrap.querySelector('[tab-active]');
    var contents = {};
    var i = 0;

    tabs.forEach(function(elem){
      attr = elem.getAttribute('tab-to');
      item = wrap.querySelector(attr);

      if(tab_active == undefined){
        if(i != 0){
          item.style.display = 'none';
          item.style.visibility = 'hidden';
        }
      } else {
        if (!elem.hasAttribute('tab-active')){
          item.style.display = 'none';
          item.style.visibility = 'hidden';
        }
      }

      contents[attr] = item;
      i++;
    });

    tabs.forEach(function(elem){
      elem.onclick = function(e){
        e.preventDefault();
        attr = this.getAttribute('tab-to');
        contents[attr].style.display = 'block';
        contents[attr].style.visibility = 'visible';
        for (key in contents) {
          if (contents.hasOwnProperty(key)) {
            if(key != attr){
              contents[key].style.display = 'none';
              contents[key].style.visibility = 'hidden';
            }
          }
        }
      };
    });
  });


});