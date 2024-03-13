function changeContent(index) {
   var video = document.getElementById('video');
   var imageContainer = document.getElementById('image-container');

   var smallBoxes = document.getElementsByClassName('small-box');

   // Reset border color for all small boxes
   for (var i = 0; i < smallBoxes.length; i++) {
      smallBoxes[i].style.border = 'none';
   }

   // Show the selected content based on index
   if (index === 0) {
      video.style.display = 'block';
      imageContainer.style.display = 'none'; // Hide the image container
   } else {
      video.style.display = 'none'; // Hide the video
      var image = document.getElementById('image');
      image.src = smallBoxes[index].querySelector('img').src;
      imageContainer.style.display = 'block'; // Show the image container
   }

   // Set border color for the selected small box
   smallBoxes[index].style.border = '2px solid black';
}

