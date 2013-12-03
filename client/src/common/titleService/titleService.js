angular.module("titleService", [])

.factory("titleService", function($document) {
  var suffix = "", title = "";

  return {
    setSuffix: function(s) {
      suffix = s;
    },
    getSuffix: function() {
      return suffix;
    },
    setTitle: function(t) {
      if (suffix !== "") {
        title = t + suffix;
      } else {
        title = t;
      }

      $document.prop("title", title);
    },
    getTitle: function() {
      return $document.prop("title");
    }
  };
});
