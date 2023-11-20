$(document).ready(function($) {

 var cropper;

 var dataImage;

 var itemFU;

 var typeCurrent;



   if($('.uploader').length > 0){
    $('.uploader').fineUploader({
            template: 'qq-template',
            multiple : false,
            request: {
                endpoint: baseRoot+'/admin/ajax/images'
            },
            thumbnails: {
                placeholders: {
                    waitingPath: baseRoot+'/fineuploader/placeholders/waiting-generic.png',
                    notAvailablePath: baseRoot+'/fineuploader/placeholders/not_available-generic.png'
                }
            },

            validation: {
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },

            callbacks: {
             onError: function(id, name, errorReason, xhrOrXdr) {
                     alert(errorReason);
              },
              onComplete : function(id , name , responseJSON){
                dataImage = responseJSON;
                if(dataImage.status == true){
                  openModalCrop();
                } 
              }
           }

        }).on("submitted", function(event, id) {
           itemFU = $(this);
           typeCurrent = $(this).data('t');
           var token = $('meta[name="csrf-token"]').attr('content');
           $(this).fineUploader('setParams', {type: typeCurrent , id : itemFU.data('i'), _token : token , form:originForm}, id);
    });

   }

    $.each(images, function(item, val) {
        var html ='<li class="qq-file-id-0" qq-file-id="0">'+
         '<img class="qq-thumbnail-selector" qq-server-scale="" src="'+baseRoot+'/uploads/'+val+'" idth="100"></li>';
         $('#fu_'+item).find('.qq-uploader').css('background-image', 'none');
         $('#fu_'+item).find('.qq-upload-list').html(html);
    });



   function openModalCrop(){
     $('#btnCrop').text('Guardar');
     $('#btnCrop').removeAttr('disabled');
     $('#imgCrop').attr('src' , baseRoot + '/uploads/'+dataImage.image); 
     var imageCp = document.getElementById('imgCrop');
     var cropBoxData;
     var canvasData;
     var ratioW = 1;
     var ratioH = 1;
     if(typeCurrent != 'logo'){
        ratioW = 37;
        ratioH = 25;
     }

     cropper = new Cropper(imageCp, {
        dragMode: 'move',
        aspectRatio: ratioW / ratioH,
        autoCropArea: 0.65,
        restore: false,
        guides: false,
        center: false,
        highlight: false,
        cropBoxMovable: false,
        cropBoxResizable: false,
        toggleDragModeOnDblclick: false,
        minContainerWidth : 400,
        minContainerHeight : 200,
        minCropBoxWidth: 150,
        minCropBoxHeight: 350,
          ready: function () {
            // Strict mode: set crop box data first
            cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
          }

        });    

     $('#modalCrop').modal('show');
   }



   $('#modalCrop').on('hidden.bs.modal', function () {
        cropper.destroy();
   });



   $('#btnCrop').click(function(event) {
     $('#btnCrop').text('Procesando...');
     $('#btnCrop').attr('disabled' , 'disabled');
     var imageCropped = cropper.getCroppedCanvas().toDataURL("image/jpg"); 
    // $('#image1').attr('src', crop);
     updateImageCropped(imageCropped);     

   });



   function updateImageCropped(imageCropped){      

      dataImage.image_base = imageCropped;
      dataImage.form = originForm;
      dataImage._token = $('meta[name="csrf-token"]').attr('content');
      $.ajax({
        url : baseRoot +'/admin/ajax/images_crop',
        type : 'POST',
        dataType : 'json',
        data : dataImage,
        success : function(response){
          if(response.status == true){
            var html ='<li class="qq-file-id-0" qq-file-id="0">'+
            '<img class="qq-thumbnail-selector" qq-server-scale="" src="'+baseRoot+'/uploads/'+response.name+'" width="100"></li>';
           itemFU.find('.qq-uploader').css('background-image', 'none');
           itemFU.find('.qq-upload-list').html(html);
           $('#modalCrop').modal('hide');
          }
        }
      });
   }
   

});









