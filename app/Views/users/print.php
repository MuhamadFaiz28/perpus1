<h3>Data Users</h3>

<table border="1" cellpadding="5">
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Username</th>
    <th>Role</th>
</tr>

<?php $no=1; foreach($users as $u): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $u['nama'] ?></td>
    <td><?= $u['username'] ?></td>
    <td><?= $u['role'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<script>
    window.print();
</script>