-- UPDATE untuk menghapus "uploads/"
UPDATE entity_images 
SET path = REPLACE(path, 'uploads/', '') 
WHERE path LIKE 'uploads/%';