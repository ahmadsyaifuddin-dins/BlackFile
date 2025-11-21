-- UPDATE hanya untuk data yang dimulai dengan entity_images/
UPDATE entity_images 
SET path = CONCAT('uploads/', path) 
WHERE path LIKE 'entity_images/%';