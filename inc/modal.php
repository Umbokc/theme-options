<div id="modal-add-section" class="modal">
	<div class="bg-modal"></div>
	<div class="modal__box">
		<form>
			<div>
				<label>Enter alias</label>
				<input type="text" name='alias' value="services" required>
			</div>
			<div>
				<label>Enter name</label>
				<input type="text" name='name' value="The name" required>
			</div>
			<div>
				<label>Select type</label>
				<select name="type" required>
					<option value="section">Section</option>
					<option value="sub-section">Has sub sections</option>
				</select>
			</div>
			<br>
			<br>
			<input type="submit" class="u-btn-primary" value="Создать" m:h:a>
		</form>
	</div>
</div>
<div id="modal-add-field" class="modal">
	<div class="bg-modal"></div>
	<div class="modal__box">
		<form>
			<div>
				<label>Enter alias</label>
				<input type="text" name='alias' value="field" required>
			</div>
			<div>
				<label>Enter name</label>
				<input type="text" name='name' value="The field" required>
			</div>
			<div>
				<label>Select type</label>
				<select name="type" required>
					<option value="input">input</option>
					<option value="textarea">textarea</option>
					<option value="wp_editor">wp_editor</option>
					<option value="tab">tab</option>
					<option value="array">array</option>
					<option value="img">img</option>
					<option value="h2">h2</option>
					<option value="h3">h3</option>
				</select>
			</div>
			<div>
				<label>Default value</label>
				<input type="text" name='default' value="The value" required>
				<textarea name='default' class="d:n" mh:100px>The value</textarea>
			</div>
			<div class="d:n">
				<label>Array type</label>
				<select name="array-type" required>
					<option value="input">input</option>
					<option value="textarea">textarea</option>
					<option value="wp_editor">wp_editor</option>
					<option value="tab">tab</option>
					<option value="array">array</option>
					<option value="img">img</option>
					<option value="h2">h2</option>
					<option value="h3">h3</option>
				</select>
			</div>
			<br>
			<br>
			<input type="submit" class="u-btn-primary" value="Создать" m:h:a>
		</form>
	</div>
</div>
<div id="modal-add-array" class="modal">
	<div class="bg-modal"></div>
	<div class="modal__box">
		<form>
			<div>
				<label>Введите название</label>
				<input type="text" name='name' value="The name" required>
			</div>
			<br>
			<br>
			<input type="submit" class="u-btn-primary" value="Добавить" m:h:a>
		</form>
	</div>
</div>
<div id="modal-success" class="modal">
	<div class="modal__box">
		<p>Сохранение прошло успешно</p>
	</div>
</div>
<div id="modal-processiog" class="modal">
	<div class="modal__box">
		<p>Сохраняю...</p>
	</div>
</div>