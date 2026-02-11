using New_Homepage.Models;

namespace New_Homepage.Services;

public interface IEquipmentService
{
    Task<List<Equipment>> GetAllEquipmentsAsync();
    Task<List<Equipment>> GetEquipmentsByCategoryAsync(string category);
    Task<Equipment?> GetEquipmentByIdAsync(string equipmentId);
    Task<List<WipData>> GetWipTrendDataAsync(int days = 7);
}
