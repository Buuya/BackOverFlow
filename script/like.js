document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', function () {
        const questionId = this.getAttribute('data-id');
        fetch('/php/like.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `question_id=${questionId}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => console.error('Error:', error));
    });
});
