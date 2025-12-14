<?php

namespace Tests\Unit;

use App\Models\Cabang;
use App\Models\Kamar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KamarTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_tambah_kamar(): void
    {
        $cabang = Cabang::create([
            'nama_cabang' => 'Pondok Siti Hajar',
            'deskripsi' => fake()->text(300),
            'jumlah_kamar' => 30,
            'lokasi' => 'Parepare',
            'kategori_cabang' => 'kost',
        ]);

        $kamar = Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '111',
            'tipe_kamar' => 'Standar',
            'harga_kamar' => 100000,
            'deskripsi' => 'Kamar Standar.',
            'status' => 'Tersedia',
        ]);

        $this->assertInstanceOf(Kamar::class, $kamar);
        $this->assertNotNull($kamar->id_kamar);
        $this->assertEquals('111', $kamar->no_kamar);
    }

    public function test_read_kamar()
    {
        $cabang = Cabang::create([
                'nama_cabang' => 'Pondok Siti Hajar',
                'deskripsi' => fake()->text(300),
                'jumlah_kamar' => 30,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
        ]);

        Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '100',
            'tipe_kamar' => 'Standar',
            'harga_kamar' => 50000,
            'deskripsi' => 'Kamar Standar.',
            'status' => 'Tersedia',
        ]);
        Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '200',
            'tipe_kamar' => 'Ekonomis',
            'harga_kamar' => 250000,
            'deskripsi' => 'Kamar Ekonomis.',
            'status' => 'Tersedia',
        ]);

        $daftarKamar = Kamar::all();
        $kamarPertama = $daftarKamar->first();

        // Assert
        $this->assertNotEmpty($daftarKamar);
        $this->assertCount(2, $daftarKamar);
        $this->assertStringContainsString('Standar', $kamarPertama->deskripsi);
    }

    public function test_edit_kamar()
    {
        $cabang = Cabang::create([
                'nama_cabang' => 'Pondok Siti Hajar',
                'deskripsi' => fake()->text(300),
                'jumlah_kamar' => 30,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
        ]);

        $kamar = Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '111',
            'tipe_kamar' => 'Standar',
            'harga_kamar' => 100000,
            'deskripsi' => 'Kamar Standar.',
            'status' => 'Tersedia',
        ]);

        $kamar->update([
            'harga_kamar' => 150000,
            'status' => 'Dihuni'
        ]);

        $kamarUpdate = Kamar::find($kamar->id_kamar);
        $this->assertEquals(150000, $kamarUpdate->harga_kamar);
        $this->assertNotEquals( 300000, $kamar->harga_kamar);
        $this->assertTrue($kamarUpdate->status === 'Dihuni');
        $this->assertFalse($kamarUpdate->status === 'Tersedia');
    }

    public function test_hapus_kamar()
    {
        Storage::fake('public');
        $cabang = Cabang::create([
                'nama_cabang' => 'Pondok Siti Hajar',
                'deskripsi' => fake()->text(300),
                'jumlah_kamar' => 30,
                'lokasi' => 'Parepare',
                'kategori_cabang' => 'kost',
        ]);

        $file = UploadedFile::fake()->image('hapus.jpg');
        $path = $file->storeAs('kamar', 'hapus.jpg','public');
        //Pastikan gambar ada & tersimpan
        $this->assertFileExists(Storage::disk('public')->path($path));

        $kamar = Kamar::create([
            'id_cabang' => $cabang->id_cabang,
            'no_kamar' => '111',
            'tipe_kamar' => 'Standar',
            'harga_kamar' => 100000,
            'deskripsi' => 'Kamar Standar.',
            'status' => 'Tersedia',
            'gambar' => $path
        ]);
        $this->assertNotEmpty(Kamar::all()); // Pastikan data ada

        $kamar->delete();
        Storage::disk('public')->delete($kamar->gambar);

        // Assert
        $this->assertEmpty(Kamar::all());

        $cekKamar = Kamar::find($kamar->id_kamar);
        $this->assertNull($cekKamar);

        $gambar = Storage::disk('public')->path($path);
        $this->assertFileDoesNotExist($gambar); //Pastikan gambar sudah terhapus
    }
}
