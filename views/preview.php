<div class="preview-container">
    <h2>Preview (1 Page)</h2>
    <div class="preview-frame">
        <?php if (!empty($renderData['previewData'])): ?>
            <embed src="data:application/pdf;base64,<?php echo $renderData['previewData']; ?>" width="100%" height="100%" type="application/pdf">
        <?php else: ?>
            <p>Click "Update Preview" to see the layout.</p>
        <?php endif; ?>
    </div>
</div>
