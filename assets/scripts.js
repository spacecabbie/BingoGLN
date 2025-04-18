// JavaScript for the bingo card generator
function resetForm() {
    const form = document.getElementById('bingoForm');
    form.querySelector('input[name="pages"]').value = 1;
    form.querySelector('input[name="title"]').value = 'Bingo Card';
    form.querySelector('input[name="bg_color"]').value = '#FFFFFF';
    form.querySelector('input[name="text_color"]').value = '#000000';
}
