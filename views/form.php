<div class="form-container">
    <h2>Generate Bingo Cards</h2>
    <form method="post" id="bingoForm" enctype="multipart/form-data">
        <label>Number of A4 Pages (4 cards per page):</label>
        <input type="number" name="pages" min="1" max="10" value="<?php echo htmlspecialchars((string)$renderData['formData']['pages']); ?>" required><br><br>
        
        <label>Custom Title (same for all cards):</label><br>
        <input type="text" name="title" id="titleInput" placeholder="Enter title for all cards" value="<?php echo htmlspecialchars($renderData['formData']['title']); ?>"><br><br>
        
        <label>Background Color of Bingo Cards:</label><br>
        <input type="color" name="bg_color" value="<?php echo htmlspecialchars($renderData['formData']['bg_color']); ?>"><br><br>
        
        <label>Text Color (Letters and Numbers):</label><br>
        <input type="color" name="text_color" value="<?php echo htmlspecialchars($renderData['formData']['text_color']); ?>"><br><br>
        
        <input type="submit" name="preview" value="Update Preview">
        <input type="submit" name="generate" value="Generate PDF">
        <button type="button" onclick="resetForm()">Reset to Defaults</button>
    </form>
</div>
