<aside class="ka-sidebar-detail">
	<a href="create.html" class="btn btn-primary btn-block">Tambah</a>

	<br>

	<div class="detail-info">

		<div v-if="detail !== ''">
			<detail :detail="detail"></detail>
		</div>
		<div v-else>
			<no-select></no-select>
		</div>

	</div>
</aside>	