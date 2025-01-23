document.getElementById('comment-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    fetch('/php/comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeCommentModal();
    })
    .catch(error => console.error('Error:', error));
});
