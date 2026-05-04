const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, 'resources', 'views', 'admin');
const files = [
    path.join(viewsDir, 'users.blade.php'),
    path.join(viewsDir, 'items', 'index.blade.php'),
    path.join(viewsDir, 'trips', 'index.blade.php'),
    path.join(viewsDir, 'guides', 'index.blade.php'),
];

// Regex to match the filter styles block: from "/* Фильтры */" to the closing "}" before ".admin-table" or ".table-wrapper"
const filterStyleRegex = /\/\*\s*Фильтры\s*\*\/[\s\S]*?(?=\n\n\.admin-table|\n\n\.table-wrapper|\n<\/style>)/;

for (const file of files) {
    if (!fs.existsSync(file)) {
        console.log(`SKIP (not found): ${file}`);
        continue;
    }
    let content = fs.readFileSync(file, 'utf8');
    const before = content.length;
    content = content.replace(filterStyleRegex, '');
    const removed = before - content.length;
    if (removed > 0) {
        fs.writeFileSync(file, content, 'utf8');
        console.log(`OK: ${path.relative(viewsDir, file)} (removed ${removed} chars)`);
    } else {
        console.log(`NO MATCH: ${path.relative(viewsDir, file)}`);
    }
}
