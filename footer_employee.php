</main>

<footer>
	<p>&copy; <?php echo date("Y"); ?> Employee Management System. All rights reserved.</p>
</footer>

<!-- Modal -->
<div id="empModal" class="modal">
	<div class="modal-content">
		<span class="close-btn" onclick="closeModal()">&times;</span>
		<h3>Employee Details</h3>
		<p><strong>Employee ID:</strong> <?= $empData['emp_id'] ?></p>
		<p><strong>Name:</strong> <?= $empData['name'] ?></p>
		<p><strong>Address:</strong> <?= $empData['address'] ?></p>
		<p><strong>Job:</strong> <?= $empData['job'] ?></p>
		<p><strong>Email:</strong> <?= $empData['email'] ?></p>
		<p><strong>Phone:</strong> <?= $empData['phone'] ?></p>
		<p><strong>Status:</strong> <?= 'Hired' ?></p>
	</div>
</div>

</body>
</html>