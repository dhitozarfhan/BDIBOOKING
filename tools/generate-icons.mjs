import fs from 'fs-extra';
import path from 'path';
import sharp from 'sharp';
import pngToIco from 'png-to-ico';

const SRC = path.resolve('resources/brand/logo.svg');
const OUT = path.resolve('public/icons');

const sizes = [
  // favicon
  { name: 'favicon-16x16.png', w: 16, h: 16 },
  { name: 'favicon-32x32.png', w: 32, h: 32 },

  // apple touch
  { name: 'apple-touch-icon.png', w: 180, h: 180 },

  // android chrome / pwa
  { name: 'android-chrome-192x192.png', w: 192, h: 192 },
  { name: 'android-chrome-512x512.png', w: 512, h: 512 },
];

// util: buat versi “maskable” dengan padding aman (safe zone) 20%
async function makeMaskable(srcBuffer, outPath, size) {
  const canvas = sharp({
    create: {
      width: size,
      height: size,
      channels: 4,
      background: { r: 0, g: 0, b: 0, alpha: 0 }, // transparan
    }
  });

  // scale logo ke 60%–70% dari kanvas agar aman saat dipotong topeng bulat/rounded
  const target = Math.round(size * 0.68);

  const logo = await sharp(srcBuffer)
    .resize(target, target, { fit: 'contain' })
    .png()
    .toBuffer();

  // komposisi ke tengah
  const composite = await canvas
    .composite([{ input: logo, gravity: 'center' }])
    .png()
    .toBuffer();

  await fs.outputFile(outPath, composite);
}

async function main() {
  if (!(await fs.pathExists(SRC))) {
    console.error(`❌ Sumber logo tidak ditemukan: ${SRC}`);
    process.exit(1);
  }

  await fs.ensureDir(OUT);

  const srcBuffer = await fs.readFile(SRC);

  // 1) generate semua ukuran non-maskable
  for (const s of sizes) {
    const outFile = path.join(OUT, s.name);
    await sharp(srcBuffer)
      .resize(s.w, s.h, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
      .png()
      .toFile(outFile);
    console.log('✅', s.name);
  }

  // 2) generate maskable 512x512
  const maskable512 = path.join(OUT, 'maskable-icon-512x512.png');
  await makeMaskable(srcBuffer, maskable512, 512);
  console.log('✅ maskable-icon-512x512.png');

  // 3) buat favicon.ico dari 16 & 32
  const icoOut = path.resolve('public/favicon.ico');
  const png16 = path.join(OUT, 'favicon-16x16.png');
  const png32 = path.join(OUT, 'favicon-32x32.png');
  const icoBuf = await pngToIco([png16, png32]);
  await fs.outputFile(icoOut, icoBuf);
  console.log('✅ favicon.ico');

  // 4) info tambahan
  console.log(`\nSelesai. File ikon ada di: ${OUT}`);
  console.log('Pastikan site.webmanifest sudah menunjuk ke /icons/*.png sesuai contoh.');
}

main().catch(err => {
  console.error(err);
  process.exit(1);
});
