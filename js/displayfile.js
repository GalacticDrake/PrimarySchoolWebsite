$('#selectedFile').bind('change', function() { 
    var fileName = ''; 
    fileName = $(this).val(); 
    console.log(fileName);
    $('#file-selected').html(fileName.replace(/^.*\\/, ""));
});