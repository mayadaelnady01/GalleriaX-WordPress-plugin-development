<?php
if(!defined('WPINC')){
    exit("you can't access this file directly");
}
?>
<Style>
.gallery-wrapper {
padding: 20px;
background-color: #f9f9f9;
border-radius: 8px;
max-width: 800px;
}

.gallery-header {
margin-bottom: 20px;
padding-bottom: 10px;
text-align: left;
}

.gallery-add-btn {
background-color:rgb(52, 103, 179);
color: white;
padding: 10px 20px;
border: none;
border-radius: 3px;
font-weight: 70px;
cursor: pointer;
transition: background-color 0.3s ease;
}

.gallery-add-btn:hover {
background-color:rgb(71, 100, 155);
}

.gallery-grid {
display: flex;
flex-wrap: wrap;
gap: 20px;

}

.gallery-item {
background-color: white;
border: 1px solid #ddd;
border-radius: 5px;
padding: 10px;
width: 150px;
text-align: center;
box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.gallery-item img {
width: 100%;
height: auto;
margin-bottom: 8px;
}

.remove-image {
color: #c00000;
font-size: 14px;
cursor: pointer;
transition: color 0.3s ease;
}

.remove-image:hover {
color: #a00000; 
}

</Style>

<div class="gallery-wrapper">
    <div class="gallery-header">
    <button type="button" class="gallery-add-btn" id="galleriax-media-uploader">
        Add new image
    </button>
    <input type="hidden" id="galleriax-image-ids" name="galleriax_image_ids" value="">
    </div>
    <div class="gallery-grid">
    </div>
</div>


<script>
jQuery(document).ready(function($) {
let frame;
let imageIds = [];

$('#galleriax-media-uploader').on('click', function(e) {
e.preventDefault();
if (frame) {
    frame.open();
        return;
    }
frame = wp.media({ //built-in WordPress JavaScript function opens the media library window
        title: 'Select Images',
        button: { text: 'Upload' },
        multiple: true//allow the selection of more than one image
        });

frame.on('select', function() {
    const attachments = frame.state().get('selection').toArray();
    attachments.forEach(function(attachment) {
        const url = attachment.attributes.url;
        const id = attachment.id;
            imageIds.push(id);
            $('#galleriax-image-ids').val(imageIds.join(',')); 
            //to preview each selected image inside the gallery area before publishing
                const galleryItem = `<div class="gallery-item">  
                                    <img src="${url}" alt="Gallery Image">
                                    <p class="remove-image">Remove Image</p>
                                    </div>`;
                $('.gallery-grid').append(galleryItem);});
        });
        frame.open();
    });

    $(document).on('click', '.remove-image', function() {
        $(this).closest('.gallery-item').remove();

    });
});
</script>
