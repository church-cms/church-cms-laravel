(function() {
    var HOST = "https://churchcms.appsexpress.net/admin/page/upload"; //pass the route
  
    addEventListener("trix-attachment-add", function(event) {
        if (event.attachment.file) {
            uploadFileAttachment(event.attachment)
        }
    })
  
    function uploadFileAttachment(attachment) {
        uploadFile(attachment.file, setProgress, setAttributes)
  
        function setProgress(progress) {
            attachment.setUploadProgress(progress)
        }
  
        function setAttributes(attributes) {
            attachment.setAttributes(attributes)
        }
    }
  
    function uploadFile(file, progressCallback, successCallback) {
        var formData = createFormData(file);
        var xhr = new XMLHttpRequest();
         
        xhr.open("POST", HOST, true);
        xhr.setRequestHeader( 'X-CSRF-TOKEN', getMeta( 'csrf-token' ) );
  
        xhr.upload.addEventListener("progress", function(event) {
            var progress = event.loaded / event.total * 100
            progressCallback(progress)
        })
  
        xhr.addEventListener("load", function(event) {
            var attributes = {
                url: xhr.responseText,
                href: xhr.responseText + "?content-disposition=attachment"
            }
            successCallback(attributes)
        })
  
        xhr.send(formData)
    }
  
    function createFormData(file) {
        var data = new FormData()
        data.append("Content-Type", file.type)
        data.append("file", file)
        return data
    }
 
    function getMeta(metaName) {
        const metas = document.getElementsByTagName('meta');
       
        for (let i = 0; i < metas.length; i++) {
          if (metas[i].getAttribute('name') === metaName) {
            return metas[i].getAttribute('content');
          }
        }
       
        return '';
      }
})();