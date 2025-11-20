-- UPDATE untuk menghapus "uploads/"
-- sql
UPDATE entity_images 
SET path = REPLACE(path, 'uploads/', '') 
WHERE path LIKE 'uploads/%';