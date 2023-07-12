<form action="/dev/pusher" method="POST">
  @csrf
  <label>Title</label>
  <input type="text" name="title">
  <button type="submit">Submit</button>
</form>