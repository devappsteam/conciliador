# Modelo de Entidade e Relacionamento

Definições base para realização da construção do DER final.


## Domínio - Core

Modulo responsável por fornecer a infraestrutura consumida pelos demais.


| Entidade | Descrição | Principais Atributos (Colunas) | Relacionamentos |
| :--- | :--- | :--- | :--- |
| **Company** | Gestão de multi-empresas (Matriz/Filial ou clientes do BPO). | `id`, `cnpj`, `corporate_name`, `trade_name`, `status`, `created_at`, `updated_at`, `deleted_at` | 1:N com `Users`. |
| **User** | Profissionais e colaboradores do sistema. | `id`, `company_id`, `name`, `email`, `password`, `mfa_secret` (Chave 2FA), `mfa_enabled`, `last_login_at`, `status` | N:1 com `Company`; 1:N com `Audit_Logs`. |
| **Role** | Agrupamento lógico de permissões (ex: Admin, Analista BPO, Suporte N1). | `id`, `name`, `guard_name` (Padrão Laravel), `description`, `is_active` | Relaciona-se com `Users` e `Permissions`. |
| **Permission** | Ação específica e granular no sistema (ex: view_conciliation, approve_chargeback). | `id`, `name`, `guard_name`, `module` (Categoria para organizar no front-end) | Relaciona-se com `Roles`. |
| **Role_Permission** | Tabela pivô vinculando quais permissões pertencem a quais papéis. | `role_id`, `permission_id` | N:M entre `Roles` e `Permissions`. |
| **User_Role** | Tabela pivô vinculando usuários aos seus respectivos papéis. | `user_id`, `role_id` | N:M entre `Users` e `Roles`. |
| **Personal_Access_Token** | Controle de sessões e tokens de API via Laravel Sanctum. | `id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at` | Morfologia com `Users`. |
| **Audit_Log** | Trilha de auditoria blindada para registrar quem fez o quê. | `id`, `user_id`, `event` (created, updated, deleted), `auditable_type`, `auditable_id`, `old_values` (JSON), `new_values` (JSON), `ip_address`, `user_agent`, `created_at` | N:1 com `User`. |


## Domínio - Integrações

Módulo crítico responsável por lidar com os dados externos, arquivos EDI e bancos.


| Entidade | Descrição | Principais Atributos | Relacionamentos |
| :--- | :--- | :--- | :--- |
| **Acquirer** | Cadastro das adquirentes (Cielo, Rede, Stone). | `id`, `name`, `status` | 1:N com `Acquirer_Transactions`. |
| **Bank** | Instituições financeiras para CNAB/OpenFinance. | `id`, `code`, `name`, `status` | 1:N com `Bank_Statements`. |
| **Acquirer_Credential** | Armazena chaves de API, tokens e dados de FTP/SFTP. | `id`, `acquirer_id`, `api_key_encrypted`, `secret_encrypted` | 1:1 com `Acquirer`. |
| **Integration_Log** | Registra webhook de transações e falhas para suporte técnico. | `id`, `payload` (JSON cru), `headers`, `ip_address`, `http_status`, `created_at` | Sem relação estrita (log puro). |
| **EDI_File** | Arquivos depositados no Storage. | `id`, `file_name`, `status`, `hash` | 1:N com `EDI_File_Row`. |
| **EDI_File_Row** | Tabela temporária para as linhas lidas do arquivo antes da normalização. | `id`, `edi_file_id`, `raw_line_data`, `status` | N:1 com `EDI_File`. |



## Domínio - Conciliador

Módulo responsável pelo operacional das regras de match.


| Entidade | Descrição | Principais Atributos | Relacionamentos |
| :--- | :--- | :--- | :--- |
| **Reconciliation_Rule** | Onde o cruzamento de dados é configurado (Rules Engine). | `id`, `name`, `target_acquirer`, `tolerance_amount` | 1:N com `Rule_Condition`. |
| **Rule_Condition** | Critérios específicos lidos do banco (ex: considerar NSU + Valor). | `id`, `rule_id`, `field_to_match`, `operator`, `value` | N:1 com `Reconciliation_Rule`. |
| **Internal_Sale** | Vendas originadas no ERP/Frente de Caixa. | `id`, `company_id`, `nsu`, `amount`, `date`, `status` | 1:1 com `Reconciliation_Match`. |
| **Acquirer_Transaction** | Transação registrada pela Adquirente. | `id`, `acquirer_id`, `nsu`, `gross_amount`, `net_amount`, `mdr_fee` | N:1 com `Settlement_Batch`. |
| **Settlement_Batch** | Lote de Pagamento (Resumo de Operações) agrupando milhares de transações. | `id`, `acquirer_id`, `batch_date`, `total_net_amount` | 1:N com `Acquirer_Transaction`; concilia com `Bank_Statement`. |
| **Bank_Statement** | Lançamentos no extrato bancário. | `id`, `bank_id`, `date`, `amount`, `transaction_type` | N:1 com `Bank`. |
| **Reconciliation_Match** | O vínculo entre as pontas. | `id`, `internal_sale_id`, `acquirer_transaction_id`, `status` | Relaciona as entidades de transação. |
| **Divergence** | Gestão de Chargebacks e ajustes. | `id`, `match_id`, `type`, `reason`, `resolved_at` | 1:1 com `Reconciliation_Match`. |


## Domínio - ERP

Gestão interna, contratos e financeiro da própria empresa.


| Entidade | Descrição | Principais Atributos | Relacionamentos |
| :--- | :--- | :--- | :--- |
| **Client_Contract** | Contratos de clientes e taxas acordadas. | `id`, `company_id`, `start_date`, `agreed_mdr`, `status` | 1:1 com `Company`. |
| **Employee** | Cadastro de colaboradores (RH). | `id`, `user_id`, `cpf`, `salary`, `admission_date` | 1:1 com `User`. |
| **Financial_Payable** | Contas a pagar da própria empresa. | `id`, `description`, `due_date`, `amount`, `status` | Sem relação externa crítica. |
| **Financial_Receivable** | Cobrança dos clientes pelos serviços prestados pelo BPO. | `id`, `company_id`, `description`, `due_date`, `amount` | N:1 com `Company`. |
| **Invoice** | Gerencia a emissão de notas fiscais com status da SEFAZ. | `id`, `receivable_id`, `invoice_number`, `sefaz_status` | 1:1 com `Financial_Receivable`. |


## Domínio - Operacional e Suporte

Atendimento e fluxos de trabalho.



| Entidade | Descrição | Principais Atributos | Relacionamentos |
| :--- | :--- | :--- | :--- |
| **Ticket** | Chamados do Helpdesk (investigação de arquivos, suporte). | `id`, `requester_id`, `assigned_to`, `subject`, `status` | N:1 com `User`. |
| **Ticket_Message** | Linha do tempo das interações no chamado. | `id`, `ticket_id`, `user_id`, `message`, `created_at` | N:1 com `Ticket`. |
| **Workflow_Task** | Gestão de tarefas diárias do BPO (ex: "Baixar arquivo"). | `id`, `title`, `step`, `status`, `completed_by` | N:1 com `User`. |

