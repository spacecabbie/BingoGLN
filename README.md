# BingoGLN (Generare Loteriae Numeratae)

BingoGLN is an open-source web application designed to generate unique and random bingo cards. Created by HHaufe with assistance from Grok (developed by xAI), this project is in **Alpha 0.1**, an early stage with core functionality implemented and plans for advanced features in future releases.

## Features

- Generate bingo cards with customizable titles, background colors, and text colors.
- Preview a single page (four cards) as a PDF in the browser.
- Download multiple cards as a PDF, with four cards per A4 page.
- Include a unique code on each card for identification.
- Responsive form with a reset button to restore default settings.

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/BingoGLN.git
   cd BingoGLN
   ```

2. **Install dependencies**:
   Ensure Composer is installed, then run:
   ```bash
   composer install
   ```

3. **Configure environment**:
   Copy `.env.example` to `.env` and update with your settings (currently minimal, but will include database credentials in future releases):
   ```bash
   cp .env .env.example
   ```

4. **Set permissions** (adjust for your server):
   ```bash
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   chmod 775 storage/backgrounds backups
   ```

5. **Deploy to web server**:
   Place the `BingoGLN/` directory in your web root (e.g., `/home/username/domains/yourdomain.com/public_html/BingoGLN/` for DirectAdmin users).
   Ensure PHP 8.0+ is configured (e.g., via OpenLiteSpeed, Apache, or Nginx).

6. **Access the application**:
   Open your browser and navigate to `http://yourdomain.com/BingoGLN/`.

## Usage

1. **Fill out the form**:
   - Specify the number of A4 pages (each page contains four cards).
   - Enter a custom title for all cards.
   - Choose background and text colors using color pickers.

2. **Preview or generate**:
   - Click **Update Preview** to view a single page with four cards.
   - Click **Generate PDF** to download a PDF with all requested pages.
   - Click **Reset to Defaults** to restore the form to its initial state.

3. **Verify output**:
   - Each card includes a unique code below the title for identification.
   - The PDF fits four cards per A4 page without overflow.

## Requirements

- PHP 8.0 or higher (tested with PHP 8.3)
- Composer for dependency management
- Web server (e.g., OpenLiteSpeed, Apache, Nginx)
- Optional: MySQL for future history features

## Future Features

- Store bingo card history in a MySQL database with search by user, date, or unique code.
- Ensure absolute uniqueness in number generation across cards.
- Support background image uploads with resizing and editing.
- Implement user authentication and role-based access (e.g., admin dashboard).
- Enhance fraud prevention with unique code checksums and rate limiting.
- Add templates, multi-language support, and real-time generation status.

## Contributing

Contributions are welcome! To contribute:
1. Fork the repository.
2. Create a feature branch (`git checkout -b feature/your-feature`).
3. Commit your changes (`git commit -m 'Add your feature'`).
4. Push to the branch (`git push origin feature/your-feature`).
5. Open a pull request.

Report bugs or suggest features via the [Issues](https://github.com/yourusername/BingoGLN/issues) page.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgments

- Created by HHaufe with assistance from Grok, an AI developed by xAI.
- Built using the FPDF library (`setasign/fpdf`) for PDF generation.
