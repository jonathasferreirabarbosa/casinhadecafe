-- This script updates the database to remove the status_pedido column and clean up the status_pagamento values.

-- Step 1: Modify the status_pagamento column to allow new values temporarily.
ALTER TABLE Pedidos MODIFY COLUMN status_pagamento ENUM('pendente', 'confirmado', 'pago_total', 'expirado', 'pendente_50', 'confirmado_50') NOT NULL;

-- Step 2: Update the data to the new, cleaner values.
UPDATE Pedidos SET status_pagamento = 'pendente' WHERE status_pagamento = 'pendente_50';
UPDATE Pedidos SET status_pagamento = 'confirmado' WHERE status_pagamento = 'confirmado_50';

-- Step 3: Modify the status_pagamento column again to enforce the new, clean list of values.
ALTER TABLE Pedidos MODIFY COLUMN status_pagamento ENUM('pendente', 'confirmado', 'pago_total', 'expirado') NOT NULL;

-- Step 4: Remove the now-unused status_pedido column.
ALTER TABLE Pedidos DROP COLUMN status_pedido;
