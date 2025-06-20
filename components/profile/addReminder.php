<div id="add-reminder" class="absolute z-50 items-center justify-center hidden -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 h-svh w-svw bg-black/50">
  <form onsubmit="addReminder(); return false;" id="add-reminder-form" class="relative flex flex-col gap-2 p-8 -translate-x-1/2 -translate-y-1/2 bg-white rounded-md max-w-72 top-1/2 left-1/2">
    <button type="button" onclick="closeModal()">Close</button>
    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
    <label for="judul">Event: </label>
    <input type="text" name="judul" id="judul">
    <label for="waktu">Waktu: </label>
    <input type="time" name="waktu" id="waktu">
    <button type="submit">Simpan</button>
  </form>
</div>

<script>
  function closeModal() {
    document.getElementById('add-reminder').classList.add('hidden');
  }

  async function addReminder() {
    const formData = new FormData(document.getElementById('add-reminder-form'));

    const payload = Object.fromEntries(formData)

    const res = await fetch("/nutritrack/api/add-reminder", {
      method: 'POST',
      header: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify(payload)
    });

    const result = await res.json();
    console.log(result);

    if (result.status === 'success') {
      showFlashMessage({
        type: 'success',
        messages: result.message
      });
    } else {
      showFlashMessage({
        type: 'error',
        messages: result.message
      });
    }
  }
</script>
