// molibox is a global object coming from WordPress's wp_localize_script
molibox.galleries = {};
molibox.open = false;
molibox.current = {
  gallery: null,
  number: 0
};

function molibox_init() {
  var relTypes = molibox.relTypes.split(',');
  
  allLinks = document.getElementsByTagName('a');
  for (var i=0; i<allLinks.length; i++) {
    var link = allLinks[i];
    
    if (!molibox.enableAlways) {
      var rel = link.getAttribute('rel');
      if (!rel)
        continue;
      
      var includeLink = false;
      for (var j=0; j<relTypes.length; j++) {
        if (rel.indexOf(relTypes[i]) > -1) {
          includeLink = true;
          break;
        }
      }
      if (!includeLink)
        continue;
    }
    
    if (link.href.match(/\.(jpe?g|png|gif|bmp|tiff?)$/)) {
      if (!molibox.galleries['general'])
        molibox.galleries['general'] = [];
      
      link.setAttribute('data-gallery', 'general');
      link.setAttribute('data-number', molibox.galleries['general'].length);
      
      molibox.galleries['general'].push({
        'link': link,
        'image': null,
        'imageLoaded': false
      });
      
      if (link.addEventListener) {
        link.addEventListener('click', molibox_clickhandler, false);
      }
      else if (link.attachEvent)  {
        link.attachEvent('onclick', molibox_clickhandler);
      }
    }
  }
  
  molibox.overlay = document.createElement('div');
  molibox.overlay.id = "moliboxOverlay";
  molibox.overlay.style.backgroundColor = molibox.overlayColor;
  if (molibox.overlay.addEventListener) {
    molibox.overlay.addEventListener('click', molibox_close, false);
  }
  else if (molibox.overlay.attachEvent)  {
    molibox.overlay.onclick = molibox_close;
  }
  document.body.appendChild(molibox.overlay);
  
  molibox.box = document.createElement('div');
  molibox.box.id = "molibox";
  document.body.appendChild(molibox.box);
  
  molibox.imgBox = document.createElement('div');
  molibox.imgBox.className = "image";
  molibox.box.appendChild(molibox.imgBox);
  
  molibox.caption = document.createElement('div');
  molibox.caption.className = "caption";
  molibox.box.appendChild(molibox.caption);
  
  molibox.prevLink = document.createElement('a');
  molibox.prevLink.href = '#';
  molibox.prevLink.className = 'prev';
  molibox.prevLink.innerHTML = molibox.prevText;
  molibox.box.appendChild(molibox.prevLink);
  
  molibox.nextLink = document.createElement('a');
  molibox.nextLink.href = '#';
  molibox.nextLink.className = 'next';
  molibox.nextLink.innerHTML = molibox.nextText;
  molibox.box.appendChild(molibox.nextLink);
}

function molibox_clickhandler(e) {
  e.preventDefault();
  molibox.current.gallery = this.getAttribute('data-gallery');
  molibox.current.number = this.getAttribute('data-number');
  molibox_update();
  return false;
}

function molibox_update() {
  var elem = molibox.galleries[molibox.current.gallery][molibox.current.number];
  console.log(molibox.current.gallery, molibox.current.number, elem);
  if (!molibox.open) {
    molibox.open = true;
    molibox.overlay.style.display = 'block';
    molibox.box.style.width = '';
    molibox.box.style.height = '';
    molibox.box.style.marginLeft = '';
    molibox.box.style.marginTop = '';
    window.setTimeout(function() {
      // strange bug that this has to be in a timeout, but I can live with it
      molibox.overlay.style.opacity = molibox.overlayOpacity;
      window.setTimeout(function() {
        molibox.box.style.display = 'block';
        window.setTimeout(function() {
          // again a timeout...
          molibox_resize();
        }, 100);
      }, 200);
    }, 100);
  }
  if (!elem.imageLoaded) {
    elem.image = new Image();
    elem.image.onload = function() {
      elem.imageLoaded = true;
    }
    elem.image.src = elem.link.href;
  }
}

function molibox_resize() {
  var elem = molibox.galleries[molibox.current.gallery][molibox.current.number];
  if (!elem.imageLoaded) {
    window.setTimeout(molibox_resize, 100);
  }
  else {
    molibox.box.style.width = elem.image.width + 'px';
    molibox.box.style.height = elem.image.height + 'px';
    molibox.box.style.marginLeft = -elem.image.width/2 + 'px';
    molibox.box.style.marginTop = -elem.image.height/2 + 'px';
  }
}
    

function molibox_close() {
  molibox.open = false;
  molibox.box.style.display = 'none';
  molibox.overlay.style.opacity = '0';
  window.setTimeout(function() {
    molibox.overlay.style.display = 'none';
  }, 400);
}

if (document.addEventListener) {
  document.addEventListener('DOMContentLoaded', molibox_init, false);
}
else {
  window.onload = molibox_init;
}