function openCommentModal(questionId) {
    document.getElementById('comment-modal').style.display = 'flex';
    document.getElementById('question-id').value = questionId;
}

function closeCommentModal() {
    document.getElementById('comment-modal').style.display = 'none';
}
