<div class="">
  <h2>Catatan Konsumsi Makanan Harian</h2>
  <form action="#" method="POST" enctype="multipart/form-data">
    <table>
      <tr>
        <td><label for="nama">Nama Makanan:</label></td>
        <td><input type="text" id="nama" name="nama" required></td>
      </tr>
      <tr>
        <td><label for="catatan">Catatan Tambahan:</label></td>
        <td><textarea id="catatan" name="catatan"></textarea></td>
      </tr>
      <tr>
        <td><label for="gambar">Upload Foto Makanan:</label></td>
        <td><input type="file" id="gambar" name="gambar" accept="image/*"></td>
      </tr>
      <tr>
        <td><label for="tanggal">Tanggal Konsumsi:</label></td>
        <td><input type="date" id="tanggal" name="tanggal" required></td>
      </tr>
      <tr>
        <td><label for="jam">Jam Makan:</label></td>
        <td><input type="time" id="jam" name="jam" required></td>
      </tr>
      <tr>
        <td>Jenis Makan:</td>
        <td>
          <input type="radio" id="sarapan" name="jenis" value="Sarapan" required> <label
            for="sarapan">Sarapan</label>
          <input type="radio" id="siang" name="jenis" value="Makan Siang"> <label for="siang">Makan
            Siang</label>
          <input type="radio" id="malam" name="jenis" value="Makan Malam"> <label for="malam">Makan
            Malam</label>
          <input type="radio" id="camilan" name="jenis" value="Camilan"> <label
            for="camilan">Camilan</label>
        </td>
      </tr>
      <tr>
        <td><label for="porsi">Jumlah Konsumsi (gram):</label></td>
        <td><input type="number" id="porsi" name="porsi" required></td>
      </tr>
      <tr>
        <td>Perasaan Setelah Makan:</td>
        <td>
          <input type="checkbox" id="kenyang" name="perasaan" value="Kenyang"> <label
            for="kenyang">Kenyang</label>
          <input type="checkbox" id="lapar" name="perasaan" value="Lapar"> <label
            for="lapar">Lapar</label>
          <input type="checkbox" id="lelah" name="perasaan" value="Lelah"> <label
            for="lelah">Lelah</label>
          <input type="checkbox" id="enerjik" name="perasaan" value="Enerjik"> <label
            for="enerjik">Enerjik</label>
        </td>
      </tr>

      <tr>
        <td><label for="aktivitas">Aktivitas Setelah Makan:</label></td>
        <td>
          <select id="aktivitas" name="aktivitas">
            <option value="Duduk">Duduk</option>
            <option value="Berjalan">Berjalan</option>
            <option value="Olahraga">Olahraga</option>
          </select>
        </td>
      </tr>
      <tr>
        <td>Seberapa Kenyang?</td>
        <td><input type="range" id="kenyang_scale" name="kenyang_scale" min="1" max="10"></td>
      </tr>
      <tr>
        <td><input type="hidden" name="id_user" value="12345"></td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <input type="submit" name="submit">Simpan Data</input>
          <br>
          <br>
          <button type="remove">Hapus Data</button>
        </td>
      </tr>
    </table>
  </form>
</div>